<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Team;
use App\Models\Role;
use App\Services\FilterUsersService;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected FilterUsersService $filterUsersService
    ) {
        //
    }

    public function dashboard(Request $request)
    {
        $user = $this->userRepository->getAuthenticatedUser();
        $users = $this->filterUsersService->getFilteredUsesForAdminDashboard($request);

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