<?php

namespace App\Actions\Auth;

use App\Models\User;

class LogoutUserAction
{
    /**
     * Execute action
     *
     * @param array $data
     */
    public static function execute(User $user)
    {
        $user->currentAccessToken()->delete();
    }
}
