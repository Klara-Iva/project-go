<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teams')->insert([
            [
                'name' => 'The worst technical team ever',
                'team_leader_id' => 1,
                'project_manager_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'The best team ever',
                'team_leader_id' => 2,
                'project_manager_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}