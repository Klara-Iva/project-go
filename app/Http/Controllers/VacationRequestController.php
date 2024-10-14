<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Auth;

class VacationRequestController extends Controller
{
    public function createRequestForm()
    {
        $user = Auth::user();
        return view('vacation-request', compact('user'));
    }

    public function sendRequest(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'start_date' => 'required|date',
            'days_off' => 'required|integer|min:1', //TODO use laravels Validator to check this
        ]);

        if ($validated['days_off'] > $user->annual_leave_days) {
            session()->flash('alert', 'You cannot request more days off than the available annual leave days.');
            if ($user->role_id == 2 || $user->role_id == 3) {
                return redirect()->route('managers.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }

        }

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