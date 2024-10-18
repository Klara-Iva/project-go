<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::User();
        $users = User::with('role')->get();

        return view('admin.dashboard', compact('user', 'users'));
    }

    public function showUserDetails($id)
    {
        $user = User::findOrFail($id);
        $teams = Team::all();
        $userTeams = $user->teams->pluck('id')->toArray();

        return view('user-details', compact('user', 'teams', 'userTeams'));
    }

    public function getAddNewUserView()
    {
        $teams = Team::all();

        return view('admin.add-user', compact('teams'));
    }

}