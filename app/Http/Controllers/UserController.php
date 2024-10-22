<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateUser(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|integer|exists:roles,id',
            'team_ids' => 'nullable|array',
            'team_ids.*' => 'integer|exists:teams,id',
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use. Please use a different email address.',
            'role_id.required' => 'Role is required.',
            'role_id.integer' => 'Role must be a valid integer.',
            'team_ids.array' => 'Teams must be an array of team IDs.',
            'team_ids.*.integer' => 'Each selected team must be a valid team ID.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->save();
        $user->teams()->sync($request->input('team_ids', []));

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
    }

    public function saveUser(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'team_ids' => 'nullable|array',
            'team_ids.*' => 'integer|exists:teams,id',
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'team_ids.*.exists' => 'One or more of the selected teams are invalid or not existing.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        if (!empty($validated['team_ids'])) {
            $user->teams()->sync($validated['team_ids']);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User added successfully!');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.required' => 'New password must have a value.',
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.confirmed' => 'Passwords do not match.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        if ($user->role_id == 2 || $user->role_id == 3) {
            return redirect()->route('managers.dashboard')->with('success', 'Password has been successfully updated!');
        } else {
            return redirect()->route('employee.dashboard')->with('success', 'Password has been successfully updated!');
        }

    }

    public function showResetPasswordForm()
    {
        return view('reset-password');
    }

    public function showRequests($id)
    {
        $user = User::findOrFail($id);
        $vacationRequests = $user->vacationRequests()->get();
        return view('user-requests', compact('user', 'vacationRequests'));
    }

    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return redirect()->back()->with('error', 'Admins cannot delete themselves.');
        }

        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    public function allUsers()
    {
        $users = User::with(['teams', 'role'])->get();
        return view('all-users', compact('users'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $query = User::with(['role', 'teams', 'vacationRequests']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm, $searchColumns) {
                if (in_array('name', $searchColumns)) {
                    $q->orWhere('name', 'LIKE', "%{$searchTerm}%");
                }
                if (in_array('email', $searchColumns)) {
                    $q->orWhere('email', 'LIKE', "%{$searchTerm}%");
                }
                if (in_array('role', $searchColumns)) {
                    $q->orWhereHas('role', function ($roleQuery) use ($searchTerm) {
                        $roleQuery->where('role_name', 'LIKE', "%{$searchTerm}%");
                    });
                }
                if (in_array('teams', $searchColumns)) {
                    $q->orWhereHas('teams', function ($teamQuery) use ($searchTerm) {
                        $teamQuery->where('name', 'LIKE', "%{$searchTerm}%");
                    });
                }
                if (in_array('vacationRequests', $searchColumns)) {
                    $q->orWhereHas('vacationRequests', function ($vacationQuery) use ($searchTerm) {
                        $vacationQuery->where('start_date', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('end_date', 'LIKE', "%{$searchTerm}%");
                    });
                }
            });
        }

        $users = $query->get();
        return view('all-users', compact('users'));
    }

}