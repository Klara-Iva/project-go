<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\VacationRequestSubmitted;
use App\Repositories\UserRepository;
use App\Http\Requests\SendNewVacationRequest;

class VacationRequestController extends Controller
{
    public $user;
    public $remainingVacationDays = 0;
    public $vacationRequests;

    public function __construct(
        protected UserRepository $userRepository
    ) {
        $this->user = $this->userRepository->getAuthenticatedUser();
        $vacationRequests = VacationRequest::where('user_id', $this->user->id)->get();
        $this->remainingVacationDays($vacationRequests);
    }

    public function remainingVacationDays($vacationRequests)
    {
        $pendingDays = 0;
        foreach ($vacationRequests as $request) {
            if ($request->status == 'pending') {
                $pendingDays += $request->days_requested;
            }
        }

        $remainingVacationDays = $this->user->annual_leave_days - $pendingDays;
        $this->remainingVacationDays = max($remainingVacationDays, 0);
    }

    public function createRequestForm()
    {
        $user = $this->user;
        $remainingVacationDays = $this->remainingVacationDays;

        return view('make-new-vacation-request', compact('user', 'remainingVacationDays'));
    }

    public function sendRequest(Request $request)
    {
        $vacationRequests = VacationRequest::where('user_id', $this->user->id)->get();
        $this->remainingVacationDays($vacationRequests);
        $newRequest = new SendNewVacationRequest($this->remainingVacationDays);
        $validator = Validator::make($request->all(), $newRequest->rules(), $newRequest->messages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $existingRequests = VacationRequest::where('user_id', $request->user()->id)
            ->where('status', '!=', 'rejected')
            ->get();

        foreach ($existingRequests as $existingRequest) {
            if (
                ($validated['start_date'] >= $existingRequest->start_date && $validated['start_date'] <= $existingRequest->end_date) ||
                ($validated['end_date'] >= $existingRequest->start_date && $validated['end_date'] <= $existingRequest->end_date) ||
                ($validated['start_date'] <= $existingRequest->start_date && $validated['end_date'] >= $existingRequest->end_date)
            ) {
                return redirect()->back()->withErrors(['error' => 'Your vacation request overlaps with an existing request.']);
            }
        }

        $vacationRequest = new VacationRequest();
        $vacationRequest->user_id = $request->user()->id;
        $vacationRequest->start_date = $validated['start_date'];
        $vacationRequest->end_date = $validated['end_date'];
        $vacationRequest->days_requested = $validated['days_off'];

        if ($request->user()->role_id == 2) {
            $vacationRequest->team_leader_approved = 'approved';
        }

        if ($request->user()->role_id == 3) {
            $vacationRequest->project_manager_approved = 'approved';
        }

        event(new VacationRequestSubmitted($request->user()));

        $vacationRequest->save();
        session()->flash('success', 'Vacation request successfully submitted.');

        if ($request->user()->role_id == 2 || $request->user()->role_id == 3) {
            return redirect()->route('managers.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    }

    public function showRequestDetails($id)
    {
        $vacationRequest = VacationRequest::find($id);
        return view('employee.vacation-request-details', compact('vacationRequest'));
    }

}