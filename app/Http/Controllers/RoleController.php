<?php

namespace App\Http\Controllers;

use App\Helpers\PrimevueDatatables;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
  /**
   * Setup controller roles
   */
  function __construct()
  {
    $this->middleware('permission:read-roles', ['only' => ['index', 'show']]);
    $this->middleware('permission:create-roles', ['only' => ['create', 'store']]);
    $this->middleware('permission:update-roles', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete-roles', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    if ($request->has('dt_params')) {
      return $this->sendResponse(PrimevueDatatables::of(Role::with(['permissions']))->make(), 'Roles retrieved successfully.');
    }

    return $this->sendResponse(RoleResource::collection(Role::all()), 'Roles retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreRoleRequest $request)
  {
    // add guard name
    $request['guard_name'] = 'api';

    $role = Role::make($request->all());
    $role->save();

    return $this->sendResponse(new RoleResource($role), 'Role created successfully.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Role $role)
  {
    // include permissions
    $role->load('permissions');

    return $this->sendResponse(new RoleResource($role), 'Role retrieved successfully.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRoleRequest $request, Role $role)
  {
    // add guard name
    $request['guard_name'] = 'api';

    $role->update($request->all());


    return $this->sendResponse(new RoleResource($role), 'Role updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Role $role)
  {
    $role->delete();

    return $this->sendResponse([], 'Role deleted successfully.');
  }
}
