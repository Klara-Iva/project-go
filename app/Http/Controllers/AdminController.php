<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::User();
        if (!$userId || $user->role_id != 1) {
            return redirect()->route('login');
        }

        $vacationRequests = VacationRequest::with('user')->get();
        return view('admin.dashboard', compact('user', 'vacationRequests'));
    }
    
}