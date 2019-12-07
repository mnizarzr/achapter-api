<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class AuthController extends Controller
{

    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function register()
    {
        $emailValidator = Validator::make(
            ['email' => $this->request->email],
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
            $user->name = $this->request->name;
            $user->email = $this->request->email;
            $user->password = Hash::make($this->request->password);
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

    public function login()
    {
        $this->validate($this->request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $this->request->only(['email', 'password']);

        Auth::factory()->setTTL(60*24*7); // JWT expires in 7 days;
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 200,
            'error' => null,
            'message' => "Login success",
            'token' => $token
        ], 200);
    }


}
