<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ConductorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ScheduleManagementController;
use App\Http\Controllers\Admin\NotificationController;


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER, 'phone.verified'])->group(function () {
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Test route for role verification
    Route::get('/test-roles', [DashboardController::class, 'testRoles'])->name('test-roles');



    // Bus Management (schedule managers can manage buses)
    Route::resource('buses', BusController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);

    // AJAX route to check if plate number exists
    Route::get('/buses/check-plate/{plateNumber}', [BusController::class, 'checkPlateNumber'])->name('buses.check-plate');

    // Route Management (schedule managers can manage routes)
    Route::resource('routes', RouteController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);

    // Schedule Management (schedule managers can manage schedules)
    Route::resource('schedules', ScheduleController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::post('/schedules/{schedule}/create-next-day', [ScheduleController::class, 'createNextDaySchedule'])->name('schedules.create-next-day')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    
    // Schedule Management Dashboard
    Route::get('/schedule-management', [ScheduleManagementController::class, 'index'])->name('schedule-management.index')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/schedule-management/{id}', [ScheduleManagementController::class, 'show'])->name('schedule-management.show')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    
    

    // Booking Management (only admins can manage bookings)
    Route::resource('bookings', BookingController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // News Management (only admins can manage news)
    Route::resource('news', NewsController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Category Management (only admins can manage categories)
    Route::resource('categories', CategoryController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);



    // User Management (only admins can manage users)
    Route::resource('users', UserController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Driver Management (only admins can manage drivers)
    Route::resource('drivers', DriverController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Conductor Management (only admins can manage conductors)
    Route::resource('conductors', ConductorController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Reports (admins and schedule managers can view reports)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales')->middleware('role:'.App\Models\User::ROLE_ADMIN);
    Route::get('/reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::post('/reports/custom', [ReportController::class, 'generateCustom'])->name('reports.custom.generate')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/reports/custom/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.custom.export.pdf')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/reports/custom/export/excel', [ReportController::class, 'exportExcel'])->name('reports.custom.export.excel')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);

    // Settings (only admins can manage settings)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('role:'.App\Models\User::ROLE_ADMIN);
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('role:'.App\Models\User::ROLE_ADMIN);

    
});