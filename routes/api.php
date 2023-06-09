<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum'])->group(function () {

  Route::group(['prefix' => 'auth'], function () {
    Route::post('/sanctum/token', TokenController::class);
    Route::get('/verify', AuthController::class);
  });


  Route::apiResources([
    'users' => UserController::class,
    'messages' => MessageController::class,
  ]);

  // Route::get('/users/{user}', [UserController::class, 'show']);
  // Route::get('/users', [UserController::class, 'index']);

  Route::post('/users/auth/avatar', [AvatarController::class, 'store']);

  // Route::post('/messages', [MessageController::class, 'store']);
  // Route::get('/messages', [MessageController::class, 'index']);
});