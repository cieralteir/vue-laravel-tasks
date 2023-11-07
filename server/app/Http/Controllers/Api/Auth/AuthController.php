<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\LogoutUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Login a user
     *
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        if (User::where('email', $request->input('email'))->doesntExist()) {
            return response()->json(['message' => 'Email does not exists.'], 404);
        }

        if ($data = LoginUserAction::execute([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            return response()->json($data);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    /**
     * Register a user
     *
     * @param RegisterRequest $request
     */
    public function register(RegisterRequest $request)
    {
        $data = RegisterUserAction::execute($request->all());

        return response()->json($data);
    }

    /**
     * Logout a user
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        LogoutUserAction::execute($request->user());

        return response()->json([], 204);
    }
}
