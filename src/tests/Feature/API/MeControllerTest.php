<?php

namespace Tests\Feature\API;


use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MeControllerTest extends TestCase
{
    public function testShouldFailWhenUserIsNotAuth()
    {
        //when
        $response = $this->json('GET', '/api/auth/me');

        //then
        $response->assertStatus(401);
    }

    public function testShouldReturnUserWhenIsAuth()
    {
        //given
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $token = Auth::login($user);

        //when
        $response = $this->json('GET', '/api/auth/me', [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $token]);

        //then
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
