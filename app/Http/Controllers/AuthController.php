<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __invoke()
    {
        return $this->sendResponse([
            'user' => new UserResource(Auth::user()),
            'roles' => auth()->user()->getRoleNames(),
            'permissions' => auth()->user()->getAllPermissions()->pluck('name'),
        ], 'User retrieved successfully.');
    }
}