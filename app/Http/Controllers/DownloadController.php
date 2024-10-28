<?php

namespace App\Http\Controllers;

use App\Interfaces\SearchServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class DownloadController extends Controller
{
    public function __construct(
        protected SearchServiceInterface $searchSevice
    ) {
        //
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
        $users = $this->searchSevice->performSearch($searchTerm, $searchColumns);

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

        $users = $this->searchSevice->performSearch($searchTerm, $searchColumns);
        $pdf = PDF::loadView('all-users-pdf', compact('users'));

        return $pdf->download('users_' . now()->format('Ymd_His') . '.pdf');
    }
}
