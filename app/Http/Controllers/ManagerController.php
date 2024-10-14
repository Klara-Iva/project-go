<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VacationRequest;
use Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::User();

        if (!$userId || !($user->role_id == 3 || $user->role_id == 2)) {
            return redirect()->route('login');
        }
        $teamIds = $user->teams->pluck('id');
        $teamUsers = User::whereHas('teams', function ($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        })->pluck('id');

        $vacationRequests = VacationRequest::whereIn('user_id', $teamUsers)->with('user')->get();

        return view('managers.dashboard', compact('user', 'vacationRequests'));
    }

    public function showRequestDetails($id)
    {
        $request = VacationRequest::with('user')->findOrFail($id);
        return view('vacation-request-details', compact('request'));
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $vacationRequest = VacationRequest::findOrFail($id);

        if ($user->role_id == 2) {
            $vacationRequest->team_leader_approved = 'approved';
        } elseif ($user->role_id == 3) {
            $vacationRequest->project_manager_approved = 'approved';
        }

        $vacationRequest->save();

        if ($vacationRequest->team_leader_approved == 'approved' && $vacationRequest->project_manager_approved == 'approved') {
            $vacationRequest->status = 'approved';
            $vacationRequest->save();
            $requestingUser = $vacationRequest->user;
            $requestingUser->annual_leave_days =  $requestingUser->annual_leave_days-$vacationRequest->days_requested;
            $requestingUser->save();
        }

        return redirect()->route('managers.dashboard')->with('success', 'Request has been approved.');
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $vacationRequest = VacationRequest::findOrFail($id);

        if ($user->role_id == 2) {
            $vacationRequest->team_leader_approved = 'rejected';
        } elseif ($user->role_id == 3) {
            $vacationRequest->project_manager_approved = 'rejected';
        }

        $vacationRequest->save();

        if ($vacationRequest->team_leader_approved == 'rejected' || $vacationRequest->project_manager_approved == 'rejected') {
            $vacationRequest->status = 'rejected';
            $vacationRequest->save();
        }

        return redirect()->route('managers.dashboard')->with('success', 'Request has been rejected.');
    }

}