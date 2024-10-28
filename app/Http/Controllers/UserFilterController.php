<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserFilterController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
        //
    }

    public function getFilteredUsers(Request $request, $teamIds)
    {
        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $sortOrder = $request->input('sort_order', 'asc');
        $sortBy = $request->input('sort_by', 'name');
        $perPage = $request->input('per_page', 15);

        $query = $this->getBaseUserQuery();
        $query = $this->applyTeamFilter($query, $teamIds);
        $query = $this->applySearchFilter($query, $searchTerm, $searchColumns);
        $query = $this->applySorting($query, $sortBy, $sortOrder);

        return $this->paginateResults($query, $perPage, $request);
    }

    private function getBaseUserQuery()
    {
        return $this->userRepository->allWithRelations(['role', 'teams', 'vacationRequests']);
    }

    private function applyTeamFilter($query, $teamIds)
    {
        return $query->whereHas('teams', function ($q) use ($teamIds) {
            $q->whereIn('teams.id', $teamIds);
        });
    }

    private function applySearchFilter($query, $searchTerm, $searchColumns)
    {
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm, $searchColumns) {
                if (in_array('name', $searchColumns)) {
                    $q->orWhere('name', 'LIKE', "%{$searchTerm}%");
                }
                if (in_array('email', $searchColumns)) {
                    $q->orWhere('email', 'LIKE', "%{$searchTerm}%");
                }
            });
        }
        return $query;
    }

    private function applySorting($query, $sortBy, $sortOrder)
    {
        if ($sortBy === 'role') {
            $query->join('roles', 'users.role_id', '=', 'roles.id')
                ->select('users.*')
                ->orderBy('roles.role_name', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        return $query;
    }

    private function paginateResults($query, $perPage, Request $request)
    {
        return $query->paginate($perPage)->appends($request->all());
    }

}