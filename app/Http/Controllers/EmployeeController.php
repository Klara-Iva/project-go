<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\VacationRequest;
use App\Repositories\UserRepository;

class EmployeeController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
        //
    }
    public function dashboard()
    {
        $userId = Auth::id();
        $user = $this->userRepository->getAuthenticatedUser();
        if ($user->annual_leave_days < 0) {
            $user->annual_leave_days = 0;
            $user->save();
        }

        $vacationRequests = VacationRequest::with('user')->where('user_id', $userId)->get();
        return view('employee.dashboard', compact('user', 'vacationRequests'));
    }

}