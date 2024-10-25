<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class ManagerController extends Controller
{
    protected $user;

    public function __construct(
        protected UserRepository $userRepository,
        protected UserFilterController $userFilterController
    ) {
        $this->user = $this->userRepository->getAuthenticatedUser();
    }

    public function dashboard(Request $request)
    {
        $user = $this->user;

        if ($user->annual_leave_days < 0) {
            $user->annual_leave_days = 0;
            $user->save();
        }

        $teamIds = $user->teams->pluck('id');
        $users = $this->userFilterController->getFilteredUsers($request, $teamIds);

        return view('managers.dashboard', [
            'user' => $user,
            'teamUsers' => $users,
            'sortBy' => $request->input('sort_by', 'name'),
            'sortOrder' => $request->input('sort_order', 'asc'),
            'searchTerm' => $request->input('search_term'),
            'searchColumns' => $request->input('search_columns', [])
        ]);
    }

    public function showVacationRequestDetails($id)
    {
        $request = VacationRequest::with('user')->findOrFail($id);

        return view('managers.approve-vacation-request', compact('request'));
    }

    public function handleApproval(Request $request, $id)
    {
        $user = $this->user;
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
        $user = $this->user;
        $teamIds = $user->teams->pluck('id')->toArray();
        $teamUsers = $this->userRepository->getUsersByTeamIds($teamIds);
        $vacationRequests = VacationRequest::whereIn('user_id', $teamUsers)->with('user')->get();

        return view('managers.all-team-requests', compact('user', 'vacationRequests'));
    }


}