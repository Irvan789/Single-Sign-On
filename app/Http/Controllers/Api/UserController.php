<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        if ($request->user()->tokenCan('user:email')) {
            $userData = array_slice($data, 0, 4);
            $createnData = array_slice($data, 4);

            $userEmail = [
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ];

            $data = array_merge($userData, $userEmail, $createnData);
        }

        return response()->json($data);
    }
}
