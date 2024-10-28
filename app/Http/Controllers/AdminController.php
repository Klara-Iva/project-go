<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Team;
use App\Models\Role;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
        //
    }
    private function getFilteredUsers(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $sortOrder = $request->input('sort_order', 'asc');
        $sortBy = $request->input('sort_by', 'name');
        $perPage = $request->input('per_page', 15);

        $query = $this->userRepository->allWithRelations(['role', 'teams', 'vacationRequests']);

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

        if ($sortBy === 'role') {
            $query->join('roles', 'users.role_id', '=', 'roles.id')
                ->select('users.*')
                ->orderBy('roles.role_name', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate($perPage)->appends($request->all());
    }

    public function dashboard(Request $request)
    {
        $user = $this->userRepository->getAuthenticatedUser();
        $users = $this->getFilteredUsers($request);

        return view('admin.dashboard', [
            'user' => $user,
            'users' => $users,
            'sortBy' => $request->input('sort_by', 'name'),
            'sortOrder' => $request->input('sort_order', 'asc'),
            'searchTerm' => $request->input('search_term'),
            'searchColumns' => $request->input('search_columns', [])
        ]);
    }

    public function showUserDetails($id)
    {
        $user = $this->userRepository->find($id);
        $teams = Team::all();
        $roles = Role::all();
        $userTeams = $user->teams->pluck('id')->toArray();

        return view('admin.user-details', compact('user', 'teams', 'userTeams', 'roles'));
    }

    public function getAddNewUserView()
    {
        $teams = Team::all();
        $roles = Role::all();
        return view('admin.add-user', compact('teams', 'roles'));
    }

}