<?php

namespace App\Domain\User\Controllers;

use App\Domain\User\Models\User;
use App\Domain\User\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserIndexController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $users = User::where('id', '!=', $request->user()->id)->get();

        return UserResource::collection($users);
    }
}
