<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['error' => 'Wrong email or password!']);
        }

        Auth::login($user);

        switch ($user->role_id) {
            case 1:
                return redirect()->route('admin.dashboard')->with('success', 'Successfully logged in!');
            case 2:
                return redirect()->route('managers.dashboard')->with('success', 'Successfully logged in!');
            case 3:
                return redirect()->route('managers.dashboard')->with('success', 'Successfully logged in!');
            case 4:
                return redirect()->route('employee.dashboard')->with('success', 'Successfully logged in!');
            default:
                return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

}