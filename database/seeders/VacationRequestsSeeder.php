<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VacationRequest;
use Carbon\Carbon;

class VacationRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role_id != 5) {
                if (rand(0, 1)) {
                    $numberOfRequests = rand(1, 4);

                    for ($i = 0; $i < $numberOfRequests; $i++) {
                        $startDate = Carbon::now()->addDays(rand(1, 30));
                        $daysRequested = rand(1, min(7, $user->annual_leave_days));
                        $endDate = (clone $startDate)->addDays($daysRequested - 1);

                        VacationRequest::create([
                            'user_id' => $user->id,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'days_requested' => $daysRequested,
                        ]);

                        $user->save();

                        if ($user->annual_leave_days <= 0) {
                            break;
                        }
                    }
                }
            }
        }
    }
}
