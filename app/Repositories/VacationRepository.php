<?php

namespace App\Repositories;

use App\Models\VacationRequest;
use App\Interfaces\VacationRepositoryInterface;
use Illuminate\Http\Request;

class VacationRepository implements VacationRepositoryInterface
{
    public function all()
    {
        return VacationRequest::all();
    }

    public function create(array $data)
    {
        return VacationRequest::create($data);
    }

    public function update(array $data, $id)
    {
        $vacationRequest = $this->find($id);
        if ($vacationRequest) {
            $vacationRequest->update($data);
            return $vacationRequest;
        }
        return null;
    }

    public function delete($id)
    {
        $vacationRequest = $this->find($id);
        return $vacationRequest ? $vacationRequest->delete() : false;
    }

    public function find($id)
    {
        return VacationRequest::find($id);
    }

    public function getByUserId($userId)
    {
        return VacationRequest::with('user')->where('user_id', $userId)->get();
    }

    public function findWithUserOrFail($id)
    {
        return VacationRequest::with('user')->findOrFail($id);
    }

    public function getByTeamUsers($teamUsers)
    {
        return VacationRequest::whereIn('user_id', $teamUsers)->with('user')->get();
    }

    public function getNonRejectedByUserId($userId)
    {
        return VacationRequest::where('user_id', $userId)
            ->where('status', '!=', 'rejected')
            ->get();
    }

    public function createVacationRequest(Request $request, $validated)
    {
        $data = [
            'user_id' => $request->user()->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_requested' => $validated['days_off'],
        ];

        $vacationRequest = new VacationRequest();
        $vacationRequest->user_id = $data['user_id'];
        $vacationRequest->start_date = $data['start_date'];
        $vacationRequest->end_date = $data['end_date'];
        $vacationRequest->days_requested = $data['days_requested'];

        return $vacationRequest;
    }

    public function remainingVacationDays($vacationRequests, $user)
    {
        $pendingDays = 0;
        foreach ($vacationRequests as $request) {
            if ($request->status == 'pending') {
                $pendingDays += $request->days_requested;
            }
        }

        $remainingVacationDays = $user->annual_leave_days - $pendingDays;
        return max($remainingVacationDays, 0);
    }

    public function handleSpecialRoles(Request $request, $vacationRequest)
    {
        if ($request->user()->role_id == 2) {
            $vacationRequest->team_leader_approved = 'approved';
        }

        if ($request->user()->role_id == 3) {
            $vacationRequest->project_manager_approved = 'approved';
        }

    }

    public function hasOverlappingRequest($validated)
    {
        $existingRequests = $this->getNonRejectedByUserId(auth()->id());
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

}