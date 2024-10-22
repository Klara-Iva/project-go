<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function getFilteredUsers(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $sortOrder = $request->input('sort_order', 'asc');
        $sortBy = $request->input('sort_by', 'name');
        $perPage = $request->input('per_page', 15);

        $query = User::with(['role', 'teams', 'vacationRequests']);

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
        $user = Auth::User();
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
        $user = User::findOrFail($id);
        $teams = Team::all();
        $userTeams = $user->teams->pluck('id')->toArray();

        return view('admin.user-details', compact('user', 'teams', 'userTeams'));
    }

    public function getAddNewUserView()
    {
        $teams = Team::all();

        return view('admin.add-user', compact('teams'));
    }


    public function search(Request $request)
    {
        return $this->dashboard($request);
    }


}