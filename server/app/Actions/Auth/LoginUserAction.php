<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    /**
     * Execute action
     *
     * @param array $data
     */
    public static function execute(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        }

        return false;
    }
}
