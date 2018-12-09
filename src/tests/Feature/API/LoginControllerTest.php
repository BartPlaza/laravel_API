<?php

namespace Tests\Feature\API;


use App\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function testShouldReturnTokenWhenCredentialsDoMatch()
    {
        //given
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $credentials = ['email' => $user->email, 'password' => 'testy'];

        //when
        $response = $this->json('POST', '/api/auth/login', $credentials);

        //then
        $response->assertJsonStructure([
            'meta' => [
                'token'
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testShouldReturnUserDataWhenCredentialsDoMatch()
    {
        //given
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt('testy')]);
        $credentials = ['email' => $user->email, 'password' => 'testy'];

        //when
        $response = $this->json('POST', '/api/auth/login', $credentials);

        //then
        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function testShouldReturnErrorWhenCredentialsDoNotMatch()
    {
        //when
        $response = $this->json('POST', '/api/auth/login', ['email' => 'fake@email.gmail.com', 'password' => 'testy']);

        //then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }
}
