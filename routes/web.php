<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationRequestController;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(EnsureUserHasRole::class . ':all')->group(function () {
    Route::post('/vacation/request', [VacationRequestController::class, 'sendRequest'])->name('submitVacationRequest');
    Route::get('/vacation/request', [VacationRequestController::class, 'createRequestForm'])->name('vacation.request.view');
    Route::get('/reset-password', [UserController::class, 'showResetPasswordForm'])->name('user.showResetPasswordForm');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
});

Route::middleware(EnsureUserHasRole::class . ':Employee')->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
});

Route::middleware(EnsureUserHasRole::class . ':managers')->group(function () {
    Route::get('/managers/dashboard', [ManagerController::class, 'dashboard'])->name('managers.dashboard');
    Route::get('/request/{id}/details', [ManagerController::class, 'showRequestDetails'])->name('request.details');
    Route::post('/request/{id}/approve', [ManagerController::class, 'approve'])->name('vacation.approve');
    Route::post('/request/{id}/reject', [ManagerController::class, 'reject'])->name('vacation.reject');
    Route::get('/user/{id}/requests', [UserController::class, 'showRequests'])->name('user.requests');
    Route::get('/managers/allrequests', [ManagerController::class, 'viewallrequests'])->name('allrequests');

});

Route::middleware(EnsureUserHasRole::class . ':Admin')->group(function () {
    Route::get('/admin/addNewUser', [AdminController::class, 'getAddNewUserView'])->name('user.add');
    Route::get('/user/{id}/details', [AdminController::class, 'showUserDetails'])->name('user.details');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/saveUser', [UserController::class, 'saveUser'])->name('user.save');
    Route::post('/user/{id}', [UserController::class, 'updateUser'])->name('user.update');
});