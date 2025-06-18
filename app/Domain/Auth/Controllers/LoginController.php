<?php

namespace App\Domain\Auth\Controllers;

use App\Domain\Auth\Requests\LoginRequest;
use App\Domain\User\Models\User;
use App\Domain\User\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): UserResource
    {
        $user = User::firstWhere('email', $request->email);

        if (!$user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas, intentalo de nuevo.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth')->plainTextToken;

        return UserResource::make($user)->additional([
            'token' => $token,
        ]);
    }
}
