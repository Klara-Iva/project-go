<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface VacationRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);

    public function getByUserId($userId);

    public function findWithUserOrFail($id);

    public function getByTeamUsers($teamUsers);

    public function getNonRejectedByUserId($userId);

    public function remainingVacationDays($vacationRequests, $user);

    public function createVacationRequest(Request $request, $validated);

    public function handleSpecialRoles(Request $request, $vacationRequest);

    public function hasOverlappingRequest($validated);
}