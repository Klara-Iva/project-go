<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    private function getFilteredUsers(Request $request, $teamIds)
    {
        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $sortOrder = $request->input('sort_order', 'asc');
        $sortBy = $request->input('sort_by', 'name');
        $perPage = $request->input('per_page', 15);

        $query = User::with(['role', 'teams', 'vacationRequests'])
            ->whereHas('teams', function ($q) use ($teamIds) {
                $q->whereIn('teams.id', $teamIds);
            });

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm, $searchColumns) {
                if (in_array('name', $searchColumns)) {
                    $q->orWhere('name', 'LIKE', "%{$searchTerm}%");
                }
                if (in_array('email', $searchColumns)) {
                    $q->orWhere('email', 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        if ($sortBy === 'role') {
            $query->join('roles', 'users.role_id', '=', 'roles.id')
                ->select('users.*')
                ->orderBy('roles.role_name', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate($perPage)->appends($request->all());
    }


    public function dashboard(Request $request)
    {
        $user = Auth::user();

        if ($user->annual_leave_days < 0) {
            $user->annual_leave_days = 0;
            $user->save();
        }

        $teamIds = $user->teams->pluck('id');
        $users = $this->getFilteredUsers($request, $teamIds);

        return view('managers.dashboard', [
            'user' => $user,
            'teamUsers' => $users,
            'sortBy' => $request->input('sort_by', 'name'),
            'sortOrder' => $request->input('sort_order', 'asc'),
            'searchTerm' => $request->input('search_term'),
            'searchColumns' => $request->input('search_columns', [])
        ]);

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