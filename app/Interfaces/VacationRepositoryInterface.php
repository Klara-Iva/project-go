<?php

namespace App\Interfaces;

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

    public function createVacationRequest(array $data);
}