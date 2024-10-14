<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VacationRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/vacation/request', [VacationRequestController::class, 'sendRequest'])->name('submitVacationRequest');//this is renamed 

Route::get('/vacation/request', [VacationRequestController::class, 'createRequestForm'])->name('vacation.request.view');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');

Route::get('/managers/dashboard', [ManagerController::class, 'dashboard'])->name('managers.dashboard');

Route::get('/request/{id}/details', [ManagerController::class, 'showRequestDetails'])->name('request.details');

Route::post('/request/{id}/approve', [ManagerController::class, 'approve'])->name('vacation.approve');

Route::post('/request/{id}/reject', [ManagerController::class, 'reject'])->name('vacation.reject');