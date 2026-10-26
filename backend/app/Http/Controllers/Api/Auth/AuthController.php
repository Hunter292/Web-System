<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        

        $user = Employee::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => password_hash(hash_hmac("sha256", $validated["password"], getenv("PEPPER")),PASSWORD_ARGON2ID),
            'admin' =>  $validated['admin'],

        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $authData = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        return new AuthResource($authData);
    }

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        $user = Employee::where('email', $validated['email'])->first();

        if (!$user || !password_verify(hash_hmac("sha256", $validated["password"], getenv("PEPPER")),$user->password)) {
            return new ErrorResource([
                'message' => 'Invalid credentials',
                'status_code' => 401
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $authData = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        return new AuthResource($authData);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return new SuccessResource([
            'message' => 'Logged out successfully'
        ]);
    }


//The user() function retrieves the authenticated user's profile data using their Bearer token.

    public function user(Request $request)
    {
        return new SuccessResource([
            'message' => 'User data retrieved successfully',
            'data' => new UserResource($request->user())
        ]);
    }
}
