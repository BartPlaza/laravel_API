<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\PrivateUserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function action(RegisterUserRequest $request)
    {
        $user = $request->persist();
        $token = Auth::login($user);

        return (new PrivateUserResource($user))->additional([
            'meta' => [
                'token' => $token
            ]
        ])->response()->setStatusCode(200);
    }
}
