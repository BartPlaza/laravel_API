<?php

namespace Tests\Feature\API;


use App\Http\Middleware\JWTAuthMiddleware;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthMiddlewareTest extends TestCase
{
    /** @var JWTAuthMiddleware $middleware */
    private $middleware;
    private $response;

    public function setUp()
    {
        parent::setUp();
        $this->middleware = $this->app->make(JWTAuthMiddleware::class);
        $this->response = new Response();
    }

    public function testShouldNotPassWhenCanNotParseToken()
    {
        //given
        $request = Request::create('http://test.com');

        //when
        /** @var Response $response */
        $response = $this->middleware->handle($request, function () {
            return $this->response;
        });

        //then
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContains("The token could not be parsed from the request", $response->content());
    }

    public function testShouldNotPassWhenTokenIsInvalid()
    {
        //given
        $token = 'faketoken1234';
        $request = Request::create('http://test.com');
        $request->headers->set('Authorization', 'Bearer ' . $token);
        //This line is needed because Laravel create Request in different way than JWTAuth expects
        JWTAuth::setRequest($request);

        //when
        /** @var Response $response */
        $response = $this->middleware->handle($request, function () {
            return $this->response;
        });

        //then
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContains("Wrong number of segments", $response->content());
    }

    public function testShouldPassWhenTokenIsValid()
    {
        //given
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $token = Auth::login($user);
        $request = Request::create('http://test.com');
        $request->headers->set('Authorization', 'Bearer ' . $token);
        //This line is needed because Laravel create Request in different way than JWTAuth expects
        JWTAuth::setRequest($request);

        //when
        /** @var Response $response */
        $response = $this->middleware->handle($request, function () {
            return $this->response;
        });

        //then
        $this->assertEquals($this->response, $response);
    }

    public function testShouldNotPassWhenTokenWasBlacklisted()
    {
        //given
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $token = Auth::login($user);
        Auth::invalidate();
        $request = Request::create('http://test.com');
        $request->headers->set('Authorization', 'Bearer ' . $token);
        //This line is needed because Laravel create Request in different way than JWTAuth expects
        JWTAuth::setRequest($request);

        //when
        /** @var Response $response */
        $response = $this->middleware->handle($request, function () {
            return $this->response;
        });

        //then
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContains("The token has been blacklisted", $response->content());
    }

    public function testAttachNewTokenWhenTokenOldTokenExpired()
    {
        //given
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $token = auth()->setTTL(1)->login($user);
        $request = Request::create('http://test.com');
        $request->headers->set('Authorization', 'Bearer ' . $token);
        //This line is needed because Laravel create Request in different way than JWTAuth expects
        JWTAuth::setRequest($request);
        Carbon::setTestNow(Carbon::now()->addHour());

        //when
        /** @var Response $response */
        $response = $this->middleware->handle($request, function () {
            return $this->response;
        });

        //then
        $this->assertEquals($this->response, $response);
        $this->assertContains('Bearer', $response->headers->get('Authorization'));
    }
}
