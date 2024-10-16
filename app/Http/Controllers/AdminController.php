<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::User();
        if (!$userId || $user->role_id != 1) {
            Auth::logout();
            return redirect()->route('login');
        }

        $users = User::with('role')->get();
        return view('admin.dashboard', compact('user', 'users'));
    }

    public function showUserDetails($id)//TODO everything related to user, move to npr. UserController
    {
        $user = User::findOrFail($id);
        $teams = Team::all();
        $userTeams = $user->teams->pluck('id')->toArray();

        return view('user-details', compact('user', 'teams', 'userTeams'));
    }

    //TODO figure out the problem with pages stacking->every save is a new page in the stack
    //potential current fix, redirect admin to admin.dashboard 
    public function getAddNewUserView()
    {
        $teams = Team::all();
        return view('admin.add-user', compact('teams'));
    }

}