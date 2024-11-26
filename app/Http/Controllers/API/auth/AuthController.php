<?php

namespace App\Http\Controllers\Api\auth;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('project')->plainTextToken;
        $data = [
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,
        ];
        return response()->json(['status' => true, 'message' => 'User Account created Successfully', 'data' => $data], 201);
    }


    public function login(LoginRequest $request)
    {
        $request->validated();
        if (Auth::guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::guard('web')->user();
            if ($user) {
                $token = $user->createToken('project')->plainTextToken;
                $data = [
                    'token' => $token,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
                return response()->json(['status' => true, 'message' => 'User Account Logged in Successfully', 'data' => $data], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'User not found'], 401);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }
    }
}
