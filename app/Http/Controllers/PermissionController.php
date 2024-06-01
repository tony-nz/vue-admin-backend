<?php

namespace App\Http\Controllers;

use App\Helpers\PrimevueDatatables;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {

    $permissions = PrimevueDatatables::of(Permission::with(['roles']))->make();

    return $this->sendResponse($permissions, 'Permissions retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StorePermissionRequest $request)
  {
    // add guard name
    $request['guard_name'] = 'api';

    $permission = Permission::make($request->all());
    $permission->save();

    return $this->sendResponse(new PermissionResource($permission), 'Permission created successfully.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Permission $permission)
  {
    return $this->sendResponse(new PermissionResource($permission), 'Permission retrieved successfully.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdatePermissionRequest $request, Permission $permission)
  {
    // add guard name
    $request['guard_name'] = 'api';

    $permission->update($request->all());

    return $this->sendResponse(new PermissionResource($permission), 'Permission updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Permission $permission)
  {
    $permission->delete();

    return $this->sendResponse([], 'Permission deleted successfully.');
  }
}
