<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VacationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\VacationRequestSubmitted;
use App\Http\Requests\SendNewVacationRequest;

class VacationRequestController extends Controller
{
    public $user;
    public $vacationRequests;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected VacationRepositoryInterface $vacationRepository,
    ) {
        $this->user = $this->userRepository->getAuthenticatedUser();
        $this->vacationRequests = $this->vacationRepository->getByUserId($this->user->id);
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
        return max($remainingVacationDays, 0);
    }

    public function createRequestForm()
    {
        $user = $this->user;
        $remainingVacationDays = $this->remainingVacationDays($this->vacationRequests);

        return view('make-new-vacation-request', compact('user', 'remainingVacationDays'));
    }

    public function showRequestDetails($id)
    {
        $vacationRequest = $this->vacationRepository->find($id);
        return view('employee.vacation-request-details', compact('vacationRequest'));
    }

    public function sendRequest(Request $request)
    {
        $vacationRequests = $this->getVacationRequests();
        $newRequest = new SendNewVacationRequest();
        $newRequest->setRemainingVacationDays($this->remainingVacationDays($vacationRequests));


        $validator = Validator::make($request->all(), $newRequest->rules(), $newRequest->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if ($this->hasOverlappingRequest($validated)) {
            return response()->json(['errors' => ['error' => 'Your vacation request overlaps with an existing request.']], 422);
        }

        $vacationRequest = $this->createVacationRequest($request, $validated);
        $this->handleSpecialRoles($request, $vacationRequest);
        $vacationRequest->save();

        event(new VacationRequestSubmitted($request->user()));
        session()->flash('success', 'Vacation request successfully submitted.');

        return response()->json(['message' => 'Vacation request successfully submitted.']);
    }

    protected function getVacationRequests()
    {
        return $this->vacationRepository->getByUserId($this->user->id);
    }

    protected function validateRequest(Request $request)
    {
        $newRequest = new SendNewVacationRequest();
        $newRequest->setRemainingVacationDays($this->remainingVacationDays($this->vacationRequests));
        return Validator::make($request->all(), $newRequest->rules(), $newRequest->messages());
    }

    protected function hasOverlappingRequest($validated)
    {
        $existingRequests = $this->vacationRepository->getNonRejectedByUserId(auth()->id());
        foreach ($existingRequests as $existingRequest) {
            if (
                ($validated['start_date'] >= $existingRequest->start_date && $validated['start_date'] <= $existingRequest->end_date) ||
                ($validated['end_date'] >= $existingRequest->start_date && $validated['end_date'] <= $existingRequest->end_date) ||
                ($validated['start_date'] <= $existingRequest->start_date && $validated['end_date'] >= $existingRequest->end_date)
            ) {
                return true;
            }

        }

        return false;
    }

    protected function createVacationRequest(Request $request, $validated)
    {
        $data = [
            'user_id' => $request->user()->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_requested' => $validated['days_off'],
        ];

        return $this->vacationRepository->createVacationRequest($data);

    }

    protected function handleSpecialRoles(Request $request, $vacationRequest)
    {
        if ($request->user()->role_id == 2) {
            $vacationRequest->team_leader_approved = 'approved';
        }

        if ($request->user()->role_id == 3) {
            $vacationRequest->project_manager_approved = 'approved';
        }

    }

    protected function redirectUserBasedOnRole(Request $request)
    {
        if ($request->user()->role_id == 2 || $request->user()->role_id == 3) {
            return redirect()->route('managers.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    }

    public function deleteRequest(Request $request, $id)
    {
        $vacationRequest = $this->vacationRepository->find($id);
        if (!$vacationRequest) {
            return redirect()->back()->with('error', 'Vacation request not found.');
        }

        if ($vacationRequest->status === 'pending') {
            $vacationRequest->delete();
            return $this->redirectUserBasedOnRole($request)->with('success', 'Vacation request successfully deleted.');
        }

        return redirect()->back()->with('error', 'Cannot delete this request as it is not pending.');
    }

}