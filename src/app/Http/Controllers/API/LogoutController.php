<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {
        $token = $request->bearerToken();
        if($token) {
            Auth::logout();
            return response()->json(['message' => 'Successfully logged out'], 200);
        }
        return response()->json(['message' => 'Token not found in request'], 400);
    }
}
