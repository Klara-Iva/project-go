<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class VacationRequestController extends Controller
{
        public function createRequestForm()
    {
        $user = Auth::user();
        return view('make-new-vacation-request', compact('user'));
    }

    public function sendRequest(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'start_date' => 'required|date|after_or_equal:today',
            'days_off' => 'required|integer|min:1|max:' . $user->annual_leave_days,
        ];

        $messages = [
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'The start date cannot be in the past.',
            'days_off.required' => 'Please specify the number of days off.',
            'days_off.integer' => 'Days off must be a whole number.',
            'days_off.min' => 'You must request at least 1 day off.',
            'days_off.max' => 'You cannot request more days off than your available annual leave days (' . $user->annual_leave_days . ').',
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
        $vacationRequest->save();

        session()->flash('success', 'Vacation request successfully submitted.');

        if ($user->role_id == 2 || $user->role_id == 3) {
            return redirect()->route('managers.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }

    }

}