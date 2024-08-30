<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $loginUserData)
    {
        $user = User::where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            throw new AuthenticationException('Invalid Credentials');
        }

        return $user->createToken($user->name . '-AuthToken')->plainTextToken;
    }
}
