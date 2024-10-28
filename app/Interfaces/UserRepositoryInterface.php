<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);

    public function allWithRelations(array $relations);

    public function findByEmail(string $email);

    public function getUsersByTeamIds(array $teamIds);

    public function getAuthenticatedUser();

    public function getEmailsByRoleAndTeam($roleId, $teamId);
}