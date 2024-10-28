<?php

namespace App\Repositories;

use App\Models\VacationRequest;
use App\Interfaces\VacationRepositoryInterface;

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

    public function createVacationRequest(array $data)
    {
        $vacationRequest = new VacationRequest();
        $vacationRequest->user_id = $data['user_id'];
        $vacationRequest->start_date = $data['start_date'];
        $vacationRequest->end_date = $data['end_date'];
        $vacationRequest->days_requested = $data['days_requested'];

        return $vacationRequest;
    }

}