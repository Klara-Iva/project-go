<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DownloadController;
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

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/registered-user', [AuthController::class, 'registeredUser'])->name('registered-user');

Route::middleware(EnsureUserHasRole::class . ':All')->group(function () {
    Route::post('/vacation/request', [VacationRequestController::class, 'sendRequest'])->name('submitVacationRequest');
    Route::get('/vacation/request', [VacationRequestController::class, 'createRequestForm'])->name('vacation.request.view');
    Route::get('/reset-password', [UserController::class, 'showResetPasswordForm'])->name('user.showResetPasswordForm');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::get('/all-users', [UserController::class, 'allUsers'])->name('allUsers');
});

Route::middleware(EnsureUserHasRole::class . ':Employee')->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('/employee/request/{id}', [VacationRequestController::class, 'showRequestDetails'])->name('employee.requestDetails');

});

Route::middleware(EnsureUserHasRole::class . ':Managers')->group(function () {
    Route::get('/managers/dashboard', [ManagerController::class, 'dashboard'])->name('managers.dashboard');
    Route::get('/request/{id}/details', [ManagerController::class, 'showRequestDetails'])->name('request.details');
    Route::post('/request/{id}/approve', [ManagerController::class, 'handleApproval'])->name('vacation.approval');
    Route::get('/user/{id}/requests', [UserController::class, 'showRequests'])->name('user.requests');
    Route::get('/managers/allrequests', [ManagerController::class, 'viewAllRequests'])->name('allrequests');

});

Route::middleware(EnsureUserHasRole::class . ':Admin')->group(function () {
    Route::get('/admin/addNewUser', [AdminController::class, 'getAddNewUserView'])->name('user.add');
    Route::get('/user/{id}/details', [AdminController::class, 'showUserDetails'])->name('user.details');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/saveUser', [UserController::class, 'saveUser'])->name('user.save');
    Route::post('/user/{id}', [UserController::class, 'updateUser'])->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/users/search/download-csv', [UserController::class, 'downloadCSV'])->name('users.search.download.csv');
    Route::get('/users/search/download-pdf', [UserController::class, 'downloadPDF'])->name('users.search.download.pdf');
});