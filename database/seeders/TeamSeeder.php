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
                'name' => 'The worst team ever',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'The best team ever',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'The middle team',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'The nobody knows this team',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}