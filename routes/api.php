<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;

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
    'permissions' => PermissionController::class,
    'roles' => RoleController::class,
    'roles/{role}/users' => RoleUserController::class,
    'settings' => SettingController::class,
  ]);

  /**
   * Setting routes
   */
  Route::put('/settings', [SettingController::class, 'update']);

  /**
   * Custom user routes
   */

  Route::post('/users/{id}/lock', [UserController::class, 'lockUser']);
  Route::post('/users/{id}/unlock', [UserController::class, 'unlockUser']);
  Route::post('/users/auth/avatar', [AvatarController::class, 'store']);

});