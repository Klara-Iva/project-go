<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\VacationRequest;


class EmployeeController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::User();
        if (!$userId || $user->role_id != 4) {
            Auth::logout();
            return redirect()->route('login');
        }

        $vacationRequests = VacationRequest::with('user')->where('user_id', $userId)->get();
        return view('employee.dashboard', compact('user', 'vacationRequests'));
    }

    //TODO change password button and function

}