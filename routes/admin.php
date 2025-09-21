<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConductorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WeeklyScheduleTemplateController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,schedule_manager'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Test route for role verification
    Route::get('/test-roles', [DashboardController::class, 'testRoles'])->name('test-roles');

    // Bus Management (schedule managers can manage buses)
    Route::resource('buses', BusController::class)->middleware('role:admin,schedule_manager');

    // AJAX route to check if plate number exists
    Route::get('/buses/check-plate/{plateNumber}', [BusController::class, 'checkPlateNumber'])->name('buses.check-plate');

    // Route Management (schedule managers can manage routes)
    Route::resource('routes', RouteController::class)->middleware('role:admin,schedule_manager');

    // Schedule Management (schedule managers can manage schedules)
    Route::resource('schedules', ScheduleController::class)->middleware('role:admin,schedule_manager');
    Route::post('/schedules/{schedule}/create-next-day', [ScheduleController::class, 'createNextDaySchedule'])->name('schedules.create-next-day')->middleware('role:admin,schedule_manager');
    
    // Weekly Schedule Template Management (schedule managers can manage templates)
    Route::resource('weekly-schedule-templates', WeeklyScheduleTemplateController::class)->middleware('role:admin,schedule_manager');
    Route::get('/weekly-schedule-templates/{weekly_schedule_template}/generate-form', [WeeklyScheduleTemplateController::class, 'showGenerateForm'])->name('weekly-schedule-templates.generate-form')->middleware('role:admin,schedule_manager');
    Route::post('/weekly-schedule-templates/{weekly_schedule_template}/generate', [WeeklyScheduleTemplateController::class, 'generateSchedules'])->name('weekly-schedule-templates.generate')->middleware('role:admin,schedule_manager');

    // Booking Management (only admins can manage bookings)
    Route::resource('bookings', BookingController::class)->middleware('role:admin');

    // News Management (only admins can manage news)
    Route::resource('news', NewsController::class)->middleware('role:admin');

    // Category Management (only admins can manage categories)
    Route::resource('categories', CategoryController::class)->middleware('role:admin');

    // Facility Management (only admins can manage facilities)
    Route::resource('facilities', FacilityController::class)->middleware('role:admin');

    // User Management (only admins can manage users)
    Route::resource('users', UserController::class)->middleware('role:admin');

    // Driver Management (only admins can manage drivers)
    Route::resource('drivers', DriverController::class)->middleware('role:admin');

    // Conductor Management (only admins can manage conductors)
    Route::resource('conductors', ConductorController::class)->middleware('role:admin');

    // Reports (admins and schedule managers can view reports)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:admin,schedule_manager');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales')->middleware('role:admin');
    Route::get('/reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy')->middleware('role:admin,schedule_manager');
    Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom')->middleware('role:admin,schedule_manager');
    Route::post('/reports/custom', [ReportController::class, 'generateCustom'])->name('reports.custom.generate')->middleware('role:admin,schedule_manager');

    // Settings (only admins can manage settings)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('role:admin');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('role:admin');

    // Test route for sidebar issues
    Route::get('/test-sidebar', function () {
        return view('admin.test-sidebar');
    })->name('test-sidebar');
});