<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $emailValidator = Validator::make(
            ['email' => $request->email],
            ['email' => 'required|email|unique:tbl_user']
        );

        if ($emailValidator->fails()) {
            return response()->json([
                'status' => 409,
                'error' => [
                    'code' => 'ERR_EMAIL_NOT_AVAILABLE',
                    'message' => 'The email has already been taken.'
                ]
            ], 409);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';

            $user->save();
            return response()->json([
                'status' => 201,
                'error' => null,
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'message' => 'User Registration Failed!'], 409);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 200,
            'error' => null,
            'message' => "Login success",
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

}
