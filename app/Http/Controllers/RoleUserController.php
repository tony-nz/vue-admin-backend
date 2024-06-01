<?php

namespace App\Http\Controllers;

use App\Helpers\PrimevueDatatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\RoleResource;
use App\Models\User;
use DB;

class RoleUserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  function __construct()
  {
    // $this->middleware('permission:roles-read|roles-create|roles-update|roles-delete', ['only' => ['index', 'store']]);
    $this->middleware('permission:read-roles', ['only' => ['index', 'store']]);
    $this->middleware('permission:create-roles', ['only' => ['create', 'store']]);
    $this->middleware('permission:update-roles', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete-roles', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Role $role, Request $request)
  {
    $users = PrimevueDatatables::of(User::with("roles")->whereHas("roles", function ($q) use ($role) {
      $q->where("name", "=", $role->name);
    }))->make();
    return $this->sendResponse($users, 'Role ' . $role['name'] . ' users retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|unique:roles,name',
      'permission' => 'required',
    ]);

    $role = Role::create(['name' => $request->input('name')]);
    $role->syncPermissions($request->input('permission'));

    return $this->sendResponse(new RoleResource($role), 'Role created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $role = Role::find($id);
    $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
      ->where("role_has_permissions.role_id", $id)
      ->get();

    return $this->sendResponse([
      "data" => [
        'role' => new RoleResource($role),
        'permissions' => $rolePermissions,
      ]
    ], 'Role retrieved successfully.');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required',
      'permission' => 'required',
    ]);

    $role = Role::find($id);
    $role->name = $request->input('name');
    $role->save();

    $role->syncPermissions($request->input('permission'));

    return $this->sendResponse(new RoleResource($role), 'Role updated successfully.');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    DB::table("roles")->where('id', $id)->delete();
    return $this->sendResponse([], 'Role deleted successfully.');
  }
}