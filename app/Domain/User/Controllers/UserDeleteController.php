<?php

namespace App\Domain\User\Controllers;

use App\Domain\User\Models\User;
use App\Domain\User\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDeleteController extends Controller
{
    public function __invoke(Request $request, User $user): UserResource
    {
        if ($user->id == $request->user()->id) {
            abort(401, 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return UserResource::make($user);
    }
}
