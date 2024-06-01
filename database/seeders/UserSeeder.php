<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    \App\Models\User::factory(1)->create(
      [
        'name' => 'Administrator',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => null,
      ]
    );
    \App\Models\User::factory(20)->create();

    $user = \App\Models\User::where('email', 'admin@example.com')->first();
    $user->assignRole('admin');
  }
}
