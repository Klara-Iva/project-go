<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VacationRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected VacationRepositoryInterface $vacationRepository
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

        $vacationRequests = $this->vacationRepository->getByUserId($userId);
        return view('employee.dashboard', compact('user', 'vacationRequests'));
    }

}