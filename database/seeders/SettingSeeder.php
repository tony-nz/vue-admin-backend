<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $settings = [
      ['key' => 'site_name', 'value' => 'Article about database settings'],
      ['key' => 'site_registration', 'value' => '1'],
      ['key' => 'site_logo', 'value' => 'logo.png'],
      ['key' => 'site_favicon', 'value' => 'favicon.png'],
    ];

    foreach ($settings as $setting) {
      \App\Models\Setting::create($setting);
    }
  }
}
