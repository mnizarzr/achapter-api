<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function register()
    {
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
            if ($e->errorInfo[1] == 1062) return response()->json([
                "status" => 409,
                "error" => "ERR_EMAIL_NOT_AVAILABLE",
                "message" => "Email has been registered"
            ], 409);
            return response()->json([
                'error' => $e, 'message' => 'User Registration Failed!'
            ], 409);
        }
    }

    public function login()
    {
        $this->validate($this->request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $this->request->only(['email', 'password']);

        Auth::factory()->setTTL(60 * 24 * 7); // JWT expires in 7 days;
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::whereEmail($this->request->email)->first();
        $user->makeHidden(['role', 'created_at', 'updated_at'])->toArray();

        $tokenData = ["token" => $token, "exp_at" => 60 * 24 * 7];

        return response()->json([
            'status' => 200,
            'error' => "",
            'message' => "Login success",
            'token' => $tokenData,
            'data' => $user
        ], 200);
    }

    public function logout()
    {
        Auth::invalidate($this->request->bearerToken());
        return response()->json("Logout success");
    }

}
