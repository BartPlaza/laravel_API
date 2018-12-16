<?php

namespace Tests\Feature\API;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldReturnTokenWhenSuccess()
    {
        //given
        $data = [
            'name' => 'tester',
            'email' => 'test@test.com',
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ];

        //when
        $response = $this->json('POST', 'api/auth/register', $data);

        //then
        $response->assertJsonStructure([
            'meta' => [
                'token'
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testShouldReturnUserDataWhenSuccess()
    {
        //given
        $data = [
            'name' => 'tester',
            'email' => 'test@test.com',
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ];

        //when
        $response = $this->json('POST', 'api/auth/register', $data);

        //then
        $response->assertJsonFragment([
            'name' => 'tester',
            'email' => 'test@test.com'
        ]);
    }

    public function testShouldSaveUserInDatabaseWhenSuccess()
    {
        //given
        $data = [
            'name' => 'tester',
            'email' => 'test@test.com',
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ];

        //when
        $this->json('POST', 'api/auth/register', $data);

        //then
        $users = User::all();
        $this->assertCount(1, $users);
    }

    public function testShouldValidateRequest()
    {
        //when
        $response = $this->json('POST', 'api/auth/register');

        //then
        $response->assertStatus(422);
    }
}
