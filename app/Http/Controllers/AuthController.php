<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

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
        return $this->redirectToAssignedRoute($user->role_id);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 5,
        ]);

        Mail::to($user->email)->send(new WelcomeMail($user));
        Auth::login($user);
        return redirect('/registered-user')->with('success', 'Registracija uspješna! Dobrodošli.');
    }

    public function registeredUser()
    {
        if (auth()->user()->role_id != 5) {
            return $this->redirectToAssignedRoute(auth()->user()->role_id);
        }
        return view('registered-user');
    }

    public function redirectToAssignedRoute($role_id)
    {
        switch ($role_id) {
            case 1:
                return redirect()->route('admin.dashboard')->with('success', 'Successfully logged in!');
            case 2:
                return redirect()->route('managers.dashboard')->with('success', 'Successfully logged in!');
            case 3:
                return redirect()->route('managers.dashboard')->with('success', 'Successfully logged in!');
            case 4:
                return redirect()->route('employee.dashboard')->with('success', 'Successfully logged in!');
            case 5:
                return redirect()->route('registered-user')->with('success', 'Successfully logged in!');
            default:
                return redirect()->route('login');
        }

    }

}