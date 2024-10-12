<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VacationRequestSeeder extends Seeder
{
    public function run()
    {
        DB::table('vacation_requests')->insert([
            [
                'user_id' => 1,
                'start_date' => '2024-06-01',
                'end_date' => '2024-06-10',
                'days_requested' => 10,
                'team_leader_approved' => 1,
                'project_manager_approved' => 0,
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'start_date' => '2024-07-15',
                'end_date' => '2024-07-20',
                'days_requested' => 5,
                'team_leader_approved' => 1,
                'project_manager_approved' => 1,
                'status' => 'approved',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'start_date' => '2024-08-01',
                'end_date' => '2024-08-10',
                'days_requested' => 10,
                'team_leader_approved' => 0,
                'project_manager_approved' => 0,
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4,
                'start_date' => '2024-09-05',
                'end_date' => '2024-09-12',
                'days_requested' => 7,
                'team_leader_approved' => 1,
                'project_manager_approved' => 1,
                'status' => 'approved',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}