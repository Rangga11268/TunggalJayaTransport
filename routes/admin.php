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
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bus Management
    Route::resource('buses', BusController::class);

    // AJAX route to check if plate number exists
    Route::get('/buses/check-plate/{plateNumber}', [BusController::class, 'checkPlateNumber'])->name('buses.check-plate');

    // Other routes...
    Route::resource('routes', RouteController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('news', NewsController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('facilities', FacilityController::class);
    Route::resource('users', UserController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('conductors', ConductorController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy');
    Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom');
    Route::post('/reports/custom', [ReportController::class, 'generateCustom'])->name('reports.custom.generate');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Test route for sidebar issues
    Route::get('/test-sidebar', function () {
        return view('admin.test-sidebar');
    })->name('test-sidebar');
});