<?php

namespace App\Http\Controllers;

use App\Helpers\PrimevueDatatables;
use App\Http\Requests\User\StoreUser;
use App\Http\Requests\User\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  protected $userService;

  /**
   * Setup controller permissions
   */
  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
    $this->middleware('permission:read-users', ['only' => ['index', 'show']]);
    $this->middleware('permission:create-users', ['only' => ['create', 'store', 'changePassword']]);
    $this->middleware('permission:update-users', ['only' => ['edit', 'update', 'changePassword']]);
    $this->middleware('permission:delete-users', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if ($request->has('dt_params')) {
      return $this->sendResponse(PrimevueDatatables::of(User::with(['roles']))->make(), 'Users retrieved successfully.');
    }

    return $this->sendResponse(UserResource::collection(User::all()), 'Users retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  StoreUser $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreUser $request)
  {
    $user = User::make($request->all());
    $user->password = Hash::make($request->input('password'));

    if ($role_ids = $request->input('role_ids')) {
      $roles = Role::find($role_ids);
      $user->assignRole($roles);
    }

    $user->save();

    return $this->sendResponse(new UserResource($user), 'User created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  UpdateUser  $user
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateUser $request, User $user)
  {
    $user->update($request->except(['passwrord']));
    if ($password = $request->input('password')) {
      $user->password = Hash::make($password);
    }

    if ($role_ids = $request->input('role_ids')) {
      $roles = Role::find($role_ids);
      $user->assignRole($roles);
    }

    return $this->sendResponse(new UserResource($user), 'User updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    $user->delete();

    return $this->sendResponse([], 'User deleted successfully.');
  }

  /**
   * Lock the specified user.
   * @param \App\Models\User $user
   * @return \Illuminate\Http\Response
   */
  public function lock(User $user)
  {
    $this->userService->lockUser($user->id);

    return $this->sendResponse(new UserResource($user), 'User locked successfully.');
  }

  /**
   * Unlock the specified user.
   * @param \App\Models\User $user
   * @return \Illuminate\Http\Response
   */
  public function unlock(User $user)
  {
    $this->userService->unlockUser($user->id);

    return $this->sendResponse(new UserResource($user), 'User unlocked successfully.');
  }

  /**
   * Change password for the specified user.
   */
  public function changePassword(Request $request, User $user)
  {
    $this->validate($request, [
      'password' => 'required|confirmed|min:8',
    ]);

    $user->password = Hash::make($request->input('password'));
    $user->save();

    return $this->sendResponse(new UserResource($user), 'Password changed successfully.');
  }

}