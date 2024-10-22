<?php

use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\User\UserRegisterController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminNotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
// MOBILE

Route::get('/ping', function () {
    Log::info('Ping endpoint accessed.');
    return response()->json(['status' => 'connected']);
});

Route::post('/api/login', [UserLoginController::class, 'authenticateMobile']);

Route::get('/', function () {
    return view('just');
});



Route::prefix('account')->middleware('user.guest')->group(function () {
    Route::get('login', [UserLoginController::class, 'create'])->name('user.login');
    Route::post('login/authenticate', [UserLoginController::class, 'authenticate'])->name('user.authenticate');

    Route::get('register', [UserRegisterController::class, 'create'])->name('user.register');
    Route::post('register/process', [UserRegisterController::class, 'processRegister'])->name('user.register.process');
});

Route::middleware('user.auth')->group(function () {});

Route::prefix('admin')->middleware('admin.guest')->group(function () {
    Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

Route::prefix('admin')->middleware(['admin.auth', 'role:admin'])->group(function () {
    //DASHBOARD
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('chart-data', [AdminDashboardController::class, 'getChartData'])->name('admin.chart-data');
    Route::get('demographic-data', [AdminDashboardController::class, 'getDemographicData'])->name('admin.demographic-data');

    //EMPLOYEE
    Route::get('employee', [AdminEmployeeController::class, 'index'])->name('admin.employee');
    Route::get('employee/fetch/{id}', [AdminEmployeeController::class, 'fetch']);
    Route::post('employee/populate', [AdminEmployeeController::class, 'populate']);
    Route::post('employee/update', [AdminEmployeeController::class, 'update']);
    Route::post('employee/store', [AdminEmployeeController::class, 'store']);

    //USER
    Route::get('user', [AdminUserController::class, 'index'])->name('admin.user');
    Route::get('user/fetch/{id}', [AdminUserController::class, 'fetch']);
    Route::get('user/populate', [AdminUserController::class, 'populate']);
    Route::post('user/update', [AdminUserController::class, 'update']);

    //STAFF
    Route::get('appointment/pending', [AdminAppointmentController::class, 'viewPending'])->name('admin.pending.appointment');
    Route::post('appointment/pending/populate', [AdminAppointmentController::class, 'populatePendingAppointment']);
    Route::get('appointment/fetch/{id}', [AdminAppointmentController::class, 'fetch']);
    Route::post('appointment/confirm', [AdminAppointmentController::class, 'confirm']);
    Route::post('appointment/reject', [AdminAppointmentController::class, 'reject']);

    Route::get('appointment/list', [AdminAppointmentController::class, 'viewList'])->name('admin.appointment.list');
    Route::post('appointment/populate', [AdminAppointmentController::class, 'populateAppointmentList']);
    Route::get('notification', [AdminNotificationController::class, 'index'])->name('admin.notification');
    Route::get('transaction', [AdminNotificationController::class, 'index'])->name('admin.transaction');
    Route::get('appointment/list', [AdminAppointmentController::class, 'viewList'])->name('admin.appointment.list');
    Route::post('appointment/populate', [AdminAppointmentController::class, 'populateAppointmentList']);
    Route::get('notification', [AdminNotificationController::class, 'index'])->name('admin.notification');
    Route::get('transaction', [AdminNotificationController::class, 'index'])->name('admin.transaction');
});

Route::prefix('staff')->middleware(['admin.auth', 'role:staff'])->group(function () {
    Route::get('appointment/pending', [AdminAppointmentController::class, 'viewPending'])->name('staff.pending.appointment');
    Route::post('appointment/pending/populate', [AdminAppointmentController::class, 'populatePendingAppointment']);
    Route::get('appointment/fetch/{id}', [AdminAppointmentController::class, 'fetch']);
    Route::post('appointment/confirm', [AdminAppointmentController::class, 'confirm']);
    Route::post('appointment/reject', [AdminAppointmentController::class, 'reject']);

    Route::get('appointment/list', [AdminAppointmentController::class, 'viewList'])->name('staff.appointment.list');
    Route::post('appointment/populate', [AdminAppointmentController::class, 'populateAppointmentList']);
    Route::get('notification', [AdminNotificationController::class, 'index'])->name('staff.notification');
    Route::get('transaction', [AdminNotificationController::class, 'index'])->name('staff.transaction');
});

Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});
