<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
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

    public function allUsers()
    {
        $users = $this->userRepository->allWithRelations(['teams', 'role'])->get();
        return view('all-users', compact('users'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $users = $this->performSearch($searchTerm, $searchColumns);
        return view('all-users', compact('users'));
    }

    public function performSearch($searchTerm, $searchColumns)
    {
        $query = $this->userRepository->allWithRelations(['teams', 'role', 'vacationRequests']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm, $searchColumns) {
                $availableColumns = ['name', 'email', 'role', 'teams', 'vacationRequests'];
                $searchableColumns = empty($searchColumns) ? $availableColumns : $searchColumns;

                foreach ($searchableColumns as $column) {
                    if ($column === 'role') {
                        $q->orWhereHas('role', function ($roleQuery) use ($searchTerm) {
                            $roleQuery->where('role_name', 'LIKE', "%{$searchTerm}%");
                        });
                    } elseif ($column === 'teams') {
                        $q->orWhereHas('teams', function ($teamQuery) use ($searchTerm) {
                            $teamQuery->where('name', 'LIKE', "%{$searchTerm}%");
                        });
                    } elseif ($column === 'vacationRequests') {
                        $q->orWhereHas('vacationRequests', function ($vacationQuery) use ($searchTerm) {
                            $vacationQuery->where('start_date', 'LIKE', "%{$searchTerm}%")
                                ->orWhere('end_date', 'LIKE', "%{$searchTerm}%");
                        });
                    } else {
                        $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                    }
                }
            });
        }

        return $query->get();
    }

    public function downloadCSV(Request $request)
    {
        $cacheKey = 'download_searched_users_' . auth()->id(); //every user has his personal key->every user can download 2 times/min
        $downloadCount = Cache::get($cacheKey, 0);
        if ($downloadCount >= 2) {
            abort(429);
        }

        Cache::put($cacheKey, $downloadCount + 1, 60);

        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);
        $users = $this->performSearch($searchTerm, $searchColumns);

        $csvData = "ID,Name,Email,Role,Teams,Vacation Requests\n";
        foreach ($users as $user) {
            $role = $user->role->role_name ?? 'N/A';
            $teams = $user->teams->pluck('name')->implode(', ') ?? 'N/A';
            $vacationRequests = $user->vacationRequests->map(function ($request) {
                return "{$request->start_date} - {$request->end_date}";
            })->implode(' | ');
            $vacationRequests = $vacationRequests ?: 'N/A';
            $csvData .= "{$user->id},{$user->name},{$user->email},{$role},{$teams},{$vacationRequests}\n";
        }

        $fileName = 'users_' . now()->format('Ymd_His') . '.csv';
        Storage::disk('local')->put($fileName, $csvData);

        return Storage::download($fileName);
    }

    public function downloadPDF(Request $request)
    {
        $cacheKey = 'download_searched_users_' . auth()->id();
        $downloadCount = Cache::get($cacheKey, 0);
        if ($downloadCount >= 2) {
            abort(429);
        }

        Cache::put($cacheKey, $downloadCount + 1, 60);

        $searchTerm = $request->input('search_term');
        $searchColumns = $request->input('search_columns', []);

        $users = $this->performSearch($searchTerm, $searchColumns);
        $pdf = PDF::loadView('all-users-pdf', compact('users'));

        return $pdf->download('users_' . now()->format('Ymd_His') . '.pdf');
    }

}