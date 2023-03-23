<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    Role::create(['name' => 'admin']);

    \App\Models\User::factory(1)->create(
      [
        'name' => 'Administrator',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => null,
        'is_admin' => true,
      ]
    );
    \App\Models\User::factory(20)->create();

    $user = \App\Models\User::where('email', 'admin@example.com')->first();
    $user->assignRole('admin');

  }
}