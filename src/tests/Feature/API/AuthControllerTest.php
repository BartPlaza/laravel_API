<?php

namespace Tests\Feature\API;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user */
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['password' => bcrypt('testy')]);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testShouldReturn401WhenBadCredentials()
    {
        //when
        $response = $this->post('/api/auth/login');

        //then
        $response->assertStatus(401);
    }

    public function testShouldReturn200WhenCorrectCredentials()
    {
        //when
        $response = $this->post('/api/auth/login', ['email' => $this->user->email, 'password' => 'testy']);

        //then
        $response->assertStatus(200);
    }

    public function testShouldReturnInvalidCredentialsErrorWhenWrongEmail()
        {
            //when
            $response = $this->post('/api/auth/login', ['email' => 'fake@email.gmail.com', 'password' => 'testy']);

            //then
            $response->assertExactJson(['error' => 'invalid_credentials']);
        }

    public function testShouldReturnInvalidCredentialsErrorWhenWrongPassword()
    {
        //when
        $response = $this->post('/api/auth/login', ['email' => $this->user->email, 'password' => 'fakePassword']);

        //then
        $response->assertExactJson(['error' => 'invalid_credentials']);
    }

    public function testReturnAuthTokenWhenLoginSuccessfully()
        {
            //given
            $credentials = ['email' => $this->user->email, 'password' => 'testy'];

            //when
            $response = $this->post('/api/auth/login', $credentials);

            //then
            $response->assertJsonStructure(['token']);
        }
}
