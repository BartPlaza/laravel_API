<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PrivateUserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    public function action()
    {
        $credentials = Input::only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'errors' => [
                    'email' => ['Invalid credentials'],
                    'password' => ['Invalid credentials']
                ]
            ], 422);
        }

        return (new PrivateUserResource(Auth::user()))->additional([
            'meta' => [
                'token' => $token
            ]
        ])->response()->setStatusCode(200);
    }
}
