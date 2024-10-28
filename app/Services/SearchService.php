<?php

namespace App\Services;

use App\Interfaces\SearchServiceInterface;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class SearchService implements SearchServiceInterface
{

    public function __construct(
        protected UserRepository $userRepository
    ) {
        //
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $users = $this->performSearch($searchTerm, $searchColumns);
        return view('all-users', compact('users'));
    }

    public function performSearch($searchTerm, $searchColumns)
    {
        $query = $this->userRepository->allWithRelations(['teams', 'role', 'vacationRequests']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm, $searchColumns) {
                $availableColumns = ['name', 'email', 'role', 'teams', 'vacationRequests'];
                $searchableColumns = empty($searchColumns) ? $availableColumns : $searchColumns;

                foreach ($searchableColumns as $column) {
                    if ($column === 'role') {
                        $q->orWhereHas('role', function ($roleQuery) use ($searchTerm) {
                            $roleQuery->where('role_name', 'LIKE', "%{$searchTerm}%");
                        });
                    } elseif ($column === 'teams') {
                        $q->orWhereHas('teams', function ($teamQuery) use ($searchTerm) {
                            $teamQuery->where('name', 'LIKE', "%{$searchTerm}%");
                        });
                    } elseif ($column === 'vacationRequests') {
                        $q->orWhereHas('vacationRequests', function ($vacationQuery) use ($searchTerm) {
                            $vacationQuery->where('start_date', 'LIKE', "%{$searchTerm}%")
                                ->orWhere('end_date', 'LIKE', "%{$searchTerm}%");
                        });
                    } else {
                        $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                    }
                }
            });
        }

        return $query->get();
    }


}