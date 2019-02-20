<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    public function show()
    {
        return auth()->user();
    }

    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }
}
