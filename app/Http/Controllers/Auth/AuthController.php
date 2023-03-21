<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLogin;
use App\Http\Requests\StoreUser;
use App\Http\Resources\JSON as AuthResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
  /**
   * Create a new controller instance.
   */
  public function __construct()
  {
    // $this->middleware('auth:api', ['except' => ['login', 'logout']]);
  }

  protected function respondWithToken($token, $params = [])
  {
    $response = [
      'access_token' => $token,
      'token_type' => 'bearer',
      // 'expires_in' => auth('api')->factory()->getTTL() * 60
    ];

    // add params to response
    foreach ($params as $key => $value) {
      $response[$key] = $value;
    }

    return response()->json($response);
  }

  /**
   * Login
   * @param AuthLogin $request
   * @return \Illuminate\Http\JsonResponse
   */
  function login(AuthLogin $request)
  {
    $credentials = $request->only('email', 'password');

    if (!$token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token, ['user' => Auth::user()]);
  }

  /**
   * Logout
   * @return \Illuminate\Http\JsonResponse
   */
  function logout()
  {
    auth()->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
   * Refresh token
   * @return \Illuminate\Http\JsonResponse
   */
  function refresh()
  {
    $user = Auth::user();
    return $this->respondWithToken($user->token, $user);
  }

  /**
   * Register
   * @param StoreUser $request
   * @return AuthResource
   */
  function register(StoreUser $request)
  {
    $user = User::make($request->all());
    $user->password = Hash::make($request->input('password'));
    $user->save();

    return new AuthResource($user);
  }
}