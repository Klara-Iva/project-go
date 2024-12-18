<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->call(RoleSeeder::class);
    $this->call(TeamSeeder::class);
    $this->call(UserSeeder::class);
    $this->call(VacationRequestsSeeder::class);
  }

}