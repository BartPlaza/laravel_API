<?php

namespace Tests\Feature\API;


use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    public function testShouldLogoutUser()
    {
        //given
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $token = Auth::login($user);

        //when
        $response = $this->json('POST', '/api/auth/logout', [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $token]);

        //then
        $this->assertNull(Auth::user());
        $response->assertStatus(200);
    }

    public function testInvalidateTokenAfterLogout()
    {
        //NOT SURE IF THIS TEST IS CORRECT

        //given
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $token = Auth::login($user);

        //when
        $this->json('POST', '/api/auth/logout', [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $token]);

        //then
        $this->assertFalse(Auth::check());
    }

    public function testShouldReturnStatus400WhenNoTokenInLogoutRequest()
    {
        //when
        $response = $this->json('Post','/api/auth/logout');

        //then
        $response->assertStatus(400);
    }
}
