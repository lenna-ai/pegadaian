<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): string
    {
        $this->authorize('create', User::class);
        return 'ok';
    }
}
