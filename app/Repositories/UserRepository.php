<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, $id)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function allWithRelations(array $relations)
    {
        return User::with($relations);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function getUsersByTeamIds(array $teamIds)
    {
        return User::whereHas('teams', function ($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        })->pluck('id');
    }

    public function getAuthenticatedUser()
    {
        return Auth::user();
    }

    public function getEmailsByRoleAndTeam($roleId, $teamId)
    {
        return User::where('role_id', $roleId)
            ->whereHas('teams', function ($query) use ($teamId) {
                $query->where('teams.id', $teamId);
            })
            ->pluck('email')
            ->toArray();
    }

}