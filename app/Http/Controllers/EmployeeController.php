<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\VacationRequest;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::user();
        if ($user->annual_leave_days < 0) {
            $user->annual_leave_days = 0;
            $user->save();
        }

        $vacationRequests = VacationRequest::with('user')->where('user_id', $userId)->get();
        return view('employee.dashboard', compact('user', 'vacationRequests'));
    }

}