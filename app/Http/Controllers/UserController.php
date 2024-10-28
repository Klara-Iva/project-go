<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
        //
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ];

        $user = $this->userRepository->update($data, $id);
        $user->teams()->sync($request->input('team_ids', []));

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
    }

    public function saveUser(SaveUserRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ];

        $user = $this->userRepository->create($data);

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

        $user = $this->userRepository->getAuthenticatedUser();
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
        $user = $this->userRepository->find($id);
        $vacationRequests = $user->vacationRequests()->get();
        return view('user-requests', compact('user', 'vacationRequests'));
    }

    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return redirect()->back()->with('error', 'Admins cannot delete themselves.');
        }

        $this->userRepository->delete($user->id);
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    public function showAllUsers()
    {
        $users = $this->userRepository->allWithRelations(['teams', 'role'])->get();
        return view('all-users', compact('users'));
    }

}