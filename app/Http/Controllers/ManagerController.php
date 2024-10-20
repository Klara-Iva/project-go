<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::user();
        if ($user->annual_leave_days < 0) {
            $user->annual_leave_days = 0;
            $user->save();
        }

        $teamIds = $user->teams->pluck('id');

        $teamUsers = User::whereHas('teams', function ($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        })->get();

        return view('managers.dashboard', compact('user', 'teamUsers'));
    }


    public function showRequestDetails($id)
    {
        $request = VacationRequest::with('user')->findOrFail($id);

        return view('managers.approve-vacation-request', compact('request'));
    }

    public function handleApproval(Request $request, $id)
    {
        $user = Auth::user();
        $vacationRequest = VacationRequest::findOrFail($id);

        $comment = $request->input('comment', null);
        if ($user->role_id == 2) {
            if ($comment) {
                $vacationRequest->team_leader_comment = $comment;
            }
            $vacationRequest->team_leader_approved = $request->input('action');
        } elseif ($user->role_id == 3) {
            if ($comment) {
                $vacationRequest->project_manager_comment = $comment;
            }
            $vacationRequest->project_manager_approved = $request->input('action');
        }

        if ($vacationRequest->team_leader_approved == 'approved' && $vacationRequest->project_manager_approved == 'approved') {
            $vacationRequest->status = 'approved';
            $requestingUser = $vacationRequest->user;
            $requestingUser->annual_leave_days -= $vacationRequest->days_requested;
            $requestingUser->save();
        }

        if ($vacationRequest->team_leader_approved == 'rejected' || $vacationRequest->project_manager_approved == 'rejected') {
            $vacationRequest->status = 'rejected';
        }

        $vacationRequest->save();
        $message = $request->input('action') == 'approved' ? 'Request has been approved.' : 'Request has been rejected.';
        return redirect()->route('managers.dashboard')->with('success', $message);
    }

    public function viewAllRequests()
    {
        $user = Auth::User();
        $teamIds = $user->teams->pluck('id');

        $teamUsers = User::whereHas('teams', function ($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        })->pluck('id');

        $vacationRequests = VacationRequest::whereIn('user_id', $teamUsers)->with('user')->get();

        return view('managers.all-team-requests', compact('user', 'vacationRequests'));
    }

}