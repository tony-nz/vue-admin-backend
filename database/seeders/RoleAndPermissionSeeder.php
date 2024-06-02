<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'admin']);

    $actions = ['create', 'read', 'update', 'delete'];
    $resources = [
      'users',
      'roles',
      'settings',
      'permissions',
    ];

    $permissions = [];

    // loop through and create permissions
    foreach ($actions as $action) {
      foreach ($resources as $resource) {
        \Spatie\Permission\Models\Permission::create(['name' => $action . '-' . $resource]);
        // add permission to permissions
        $permissions[] = $action . '-' . $resource;
      }
    }

    // assign all permissions to admin
    $adminRole->givePermissionTo($permissions);

  }
}
