<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\VacationRequest;


class ManagerController extends Controller
{
    //Controller in mid of working on it
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::User();
        if (!$userId || $user->role_id != 3) { //TODO think how to check 2 and 3, team_leader doesnt exist currently
            return redirect()->route('login');
        }

        $vacationRequests = VacationRequest::with('user')->where('user_id', $userId)->get();
        return view('managers.dashboard', compact('user', 'vacationRequests'));
    }

}
//TODO manager doest get the "looged in" notifictation