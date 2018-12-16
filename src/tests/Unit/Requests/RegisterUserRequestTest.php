<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterUserRequestTest extends TestCase
{
    use RefreshDatabase;

    private $rules;

    public function setUp()
    {
        parent::setUp();
        $request = new RegisterUserRequest();
        $this->rules = $request->rules();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testNameIsRequired()
    {
        //given
        $attributes = [];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('name', $validator->failed());
    }

    public function testEmailIsRequired()
    {
        //given
        $attributes = [];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('email', $validator->failed());
    }

    public function testEmailIsValidEmail()
    {
        //given
        $attributes = [
            'email' => 'test@gmail'
        ];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('email', $validator->failed());
    }

    public function testEmailIsUnique()
    {
        //given
        User::create([
            'name' => 'tester',
            'email' => 'test@test.pl',
            'password' => bcrypt('fakepassword')
        ]);
        $attributes = [
            'email' => 'test@test.pl'
        ];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('email', $validator->failed());
    }

    public function testPasswordIsRequired()
    {
        //given
        $attributes = [];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('password', $validator->failed());
    }

    public function testPasswordHasMinimumFiveCharacters()
    {
        //given
        $attributes = ['password' => 'test'];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('password', $validator->failed());
    }

    public function testPasswordConfirmationIsRequired()
    {
        //given
        $attributes = [];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('password_confirmation', $validator->failed());
    }

    public function testPasswordConfirmationShouldMatchPassword()
    {
        //given
        $attributes = [
            'password' => 'fakepassword',
            'password_confirmation' => 'otherpassword'
        ];
        $validator = Validator::make($attributes, $this->rules);

        //when
        $validator->fails();

        //then
        $this->assertArrayHasKey('password_confirmation', $validator->failed());
    }
}
