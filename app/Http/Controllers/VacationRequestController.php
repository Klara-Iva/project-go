<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class VacationRequestController extends Controller
{
    public $remainingVacationDays = 0;

    public function __construct()
    {
        $user = Auth::user();
        $vacationRequests = VacationRequest::where('user_id', $user->id)->get();
        $approvedDays = 0;
        $pendingDays = 0;
        foreach ($vacationRequests as $request) {
            if ($request->status == 'approved') {
                $approvedDays += $request->days_requested;
            } elseif ($request->status == 'pending') {
                $pendingDays += $request->days_requested;
            }

        }
        $remainingVacationDays = 20 - $approvedDays - $pendingDays;
        $this->remainingVacationDays = $remainingVacationDays;
    }

    public function createRequestForm()
    {
        $user = Auth::user();
        $remainingVacationDays = $this->remainingVacationDays;
        return view('make-new-vacation-request', compact('user', 'remainingVacationDays'));
    }

    public function sendRequest(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'start_date' => 'required|date|after_or_equal:today',
            'days_off' => 'required|integer|min:1|max:' . $this->remainingVacationDays,
        ];

        //TODO add check that request cant be inside a request
        //onemoguci adminima slanje godisnjeg
        //izbaci da admin moze sebi mijenjati podatke!!!
        $messages = [
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'The start date cannot be in the past.',
            'days_off.required' => 'Please specify the number of days off.',
            'days_off.integer' => 'Days off must be a whole number.',
            'days_off.min' => 'You must request at least 1 day off.',
            'days_off.max' => 'You cannot request more days off than your available annual leave days (' . $this->remainingVacationDays . ').',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $vacationRequest = new VacationRequest();
        $vacationRequest->user_id = $user->id;
        $vacationRequest->start_date = $validated['start_date'];
        $vacationRequest->days_requested = $validated['days_off'];

        if ($user->role_id == 2) {
            $vacationRequest->team_leader_approved = 'approved';
        }

        if ($user->role_id == 3) {
            $vacationRequest->project_manager_approved = 'approved';
        }

        $vacationRequest->save();
        session()->flash('success', 'Vacation request successfully submitted.');

        if ($user->role_id == 2 || $user->role_id == 3) {
            return redirect()->route('managers.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }

    }

}