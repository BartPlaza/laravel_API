<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PrivateUserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api.auth:api']);
    }

    /** @return \Illuminate\Http\JsonResponse */

    public function action(Request $request)
    {
        return (new PrivateUserResource($request->user()))->response()->setStatusCode(200);
    }
}
