<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\User;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = ModelsUser::all();
        return User::collection($user);
    }

    public function create(): void
    {
        # code...
    }

    public function update(): void
    {
        # code...
    }

    public function delete(): void
    {
        # code...
    }
}
