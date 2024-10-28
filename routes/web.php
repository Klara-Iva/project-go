<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationRequestController;
use App\Http\Middleware\EnsureUserHasRole;
use App\Services\SearchService;
use Illuminate\Support\Facades\Route;

Route::get('/project-go', function () {
    return view('login');
});

Route::get('/project-go/login', function () {
    return view('login');
})->name('login');

Route::get('/project-go/register', function () {
    return view('register');
})->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/project-go/registered-user', [AuthController::class, 'registeredUser'])->name('registered-user');

Route::middleware(EnsureUserHasRole::class . ':All')->group(function () {
    Route::post('/vacation/request', [VacationRequestController::class, 'sendRequest'])->name('submitVacationRequest');
    Route::get('/project-go/vacation/request', [VacationRequestController::class, 'createRequestForm'])->name('vacation.request.view');
    Route::get('/project-go/reset-password', [UserController::class, 'showResetPasswordForm'])->name('user.showResetPasswordForm');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::get('/project-go/all-users', [UserController::class, 'showAllUsers'])->name('allUsers');
    Route::delete('/vacation-requests/{id}', [VacationRequestController::class, 'deleteRequest'])->name('deleteVacationRequest');

});

Route::middleware(EnsureUserHasRole::class . ':Employee')->group(function () {
    Route::get('/project-go/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('/project-go/employee/request/{id}', [VacationRequestController::class, 'showRequestDetails'])->name('employee.requestDetails');

});

Route::middleware(EnsureUserHasRole::class . ':Managers')->group(function () {
    Route::get('/project-go/managers/dashboard', [ManagerController::class, 'dashboard'])->name('managers.dashboard');
    Route::get('/project-go/request/{id}/details', [ManagerController::class, 'showVacationRequestDetails'])->name('request.details');
    Route::post('/request/{id}/approve', [ManagerController::class, 'handleApproval'])->name('vacation.approval');
    Route::get('/project-go/user/{id}/requests', [UserController::class, 'showRequests'])->name('user.requests');
    Route::get('/project-go/managers/allrequests', [ManagerController::class, 'viewAllRequests'])->name('allrequests');

});

Route::middleware(EnsureUserHasRole::class . ':Admin')->group(function () {
    Route::get('/project-go/admin/addNewUser', [AdminController::class, 'getAddNewUserView'])->name('user.add');
    Route::get('/project-go/user/{id}/details', [AdminController::class, 'showUserDetails'])->name('user.details');
    Route::get('/project-go/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/saveUser', [UserController::class, 'saveUser'])->name('user.save');
    Route::post('/user/{id}', [UserController::class, 'updateUser'])->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/project-go/users/search', [SearchService::class, 'search'])->name('users.search');
    Route::get('/project-go/users/search/download-csv', [DownloadController::class, 'downloadCSV'])->name('users.search.download.csv');
    Route::get('/project-go/users/search/download-pdf', [DownloadController::class, 'downloadPDF'])->name('users.search.download.pdf');
});