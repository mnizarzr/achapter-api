<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('hasJWT');
    }

    public function index()
    {
        $user = Auth::user();
        return $this->responseSuccess(200, "User fetched successfully", $user);
    }

    public function edit()
    {
        try {
            $user = User::find(Auth::user()["id"]);
            $user->name = $this->request->name ?: $user->name;
            $user->email = $this->request->email ?: $user->email;
            $user->address = $this->request->address ?: $user->address;
            $user->save();
            return $this->responseSuccess(200, "User updated", $user);
        } catch (Exception $e) {
            return $this->responseError(400, $e, "User update failed");
        }
    }
}
