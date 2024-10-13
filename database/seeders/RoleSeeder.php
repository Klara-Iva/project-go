<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'role_name' => 'Admin',
            ],
            [
                'id' => 2,
                'role_name' => 'Team Leader',
            ],
            [
                'id' => 3,
                'role_name' => 'Project Manager',
            ],
            [
                'id' => 4,
                'role_name' => 'Employee',
            ]
        ]);
    }
}