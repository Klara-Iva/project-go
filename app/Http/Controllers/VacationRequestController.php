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

    public function createRequestForm()
    {
        $user = $this->user;
        $remainingVacationDays = $this->vacationRepository->remainingVacationDays($this->vacationRequests, $user);

        return view('make-new-vacation-request', compact('user', 'remainingVacationDays'));
    }

    public function showRequestDetails($id)
    {
        $vacationRequest = $this->vacationRepository->find($id);
        return view('employee.vacation-request-details', compact('vacationRequest'));
    }

    public function sendRequest(Request $request)
    {
        $vacationRequests = $this->vacationRepository->getByUserId($this->user->id);
        $newRequest = new SendNewVacationRequest();
        $newRequest->setRemainingVacationDays($this->vacationRepository->remainingVacationDays($vacationRequests, $this->user));

        $validator = Validator::make($request->all(), $newRequest->rules(), $newRequest->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if ($this->vacationRepository->hasOverlappingRequest($validated)) {
            return response()->json(['errors' => ['error' => 'Your vacation request overlaps with an existing request.']], 422);
        }

        $vacationRequest = $this->vacationRepository->createVacationRequest($request, $validated);
        $this->vacationRepository->handleSpecialRoles($request, $vacationRequest);
        $vacationRequest->save();

        event(new VacationRequestSubmitted($request->user()));
        session()->flash('success', 'Vacation request successfully submitted.');

        return response()->json(['message' => 'Vacation request successfully submitted.']);
    }

    //why isnt this used?
    protected function validateRequest(Request $request)
    {
        $newRequest = new SendNewVacationRequest();
        $newRequest->setRemainingVacationDays($this->vacationRepository->remainingVacationDays($this->vacationRequests, $this->user));
        return Validator::make($request->all(), $newRequest->rules(), $newRequest->messages());
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