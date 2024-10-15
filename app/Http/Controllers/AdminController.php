<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::User();
        if (!$userId || $user->role_id != 1) {
            //logout the current user first, then redirect
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
       
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->save();
        $user->teams()->sync($request->input('team_ids', []));
    
        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
  
        //TODO figure out the problem with pages stacking->every save is a new page in the stack
        //potential current fix, redirect admin to admin.dashboard 
    }

    public function addNewUser()
    {
        $teams = Team::all(); 
        return view('admin.add-user',compact('teams'));
    }

    public function saveUser(Request $request)
    {
          //TODO add validations
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role,
                'team_id' => $request->team_id,
            ]);
    
            return redirect()->route('admin.dashboard')->with('success', 'User added successfully!');
        
    }

}