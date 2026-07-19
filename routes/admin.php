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
use App\Http\Controllers\Admin\CharterBookingController;


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:' . App\Models\User::ROLE_ADMIN . ',' . App\Models\User::ROLE_SCHEDULE_MANAGER, 'phone.verified'])->group(function () {
    Route::post('/import/{type}', [\App\Http\Controllers\Admin\ImportController::class, 'store'])->name('import.store')->middleware('role:' . \App\Models\User::ROLE_ADMIN);

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    if (app()->environment('local', 'development', 'testing')) {
        Route::get('/test-roles', [DashboardController::class, 'testRoles'])->name('test-roles');
    }

    // Buses — bulk-delete BEFORE resource
    Route::delete('/buses/bulk-delete', [BusController::class, 'bulkDestroy'])->name('buses.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('buses', BusController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN . ',' . App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/buses/check-plate/{plateNumber}', [BusController::class, 'checkPlateNumber'])->name('buses.check-plate');

    // Routes
    Route::delete('/routes/bulk-delete', [RouteController::class, 'bulkDestroy'])->name('routes.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('routes', RouteController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN . ',' . App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/routes/geocode', [RouteController::class, 'geocode'])->name('routes.geocode');

    // Schedules
    Route::delete('/schedules/bulk-delete', [ScheduleController::class, 'bulkDestroy'])->name('schedules.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN . ',' . App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::resource('schedules', ScheduleController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN . ',' . App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::post('/schedules/{schedule}/create-next-day', [ScheduleController::class, 'createNextDaySchedule'])->name('schedules.create-next-day');

    // Dashboard Jadwal
    Route::get('/schedule-management', [ScheduleManagementController::class, 'index'])->name('schedule-management.index');
    Route::get('/schedule-management/{id}', [ScheduleManagementController::class, 'show'])->name('schedule-management.show');

    // Bookings
    Route::delete('/bookings/bulk-delete', [BookingController::class, 'bulkDestroy'])->name('bookings.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('bookings', BookingController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // Charter Bookings
    Route::delete('/charter-bookings/bulk-delete', [CharterBookingController::class, 'bulkDestroy'])->name('charter-bookings.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('charter-bookings', CharterBookingController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // News
    Route::post('news/upload-image', [NewsController::class, 'uploadImage'])->name('news.upload-image');
    Route::delete('/news/bulk-delete', [NewsController::class, 'bulkDestroy'])->name('news.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('news', NewsController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // Promo Codes
    Route::delete('/promo-codes/bulk-delete', [App\Http\Controllers\Admin\PromoCodeController::class, 'bulkDestroy'])->name('promo-codes.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('promo-codes', App\Http\Controllers\Admin\PromoCodeController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // Categories
    Route::delete('/categories/bulk-delete', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('categories', CategoryController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // Users
    Route::resource('users', UserController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // Customers
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{email}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');

    // Drivers
    Route::delete('/drivers/bulk-delete', [DriverController::class, 'bulkDestroy'])->name('drivers.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('drivers', DriverController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // Conductors
    Route::delete('/conductors/bulk-delete', [ConductorController::class, 'bulkDestroy'])->name('conductors.bulk-destroy')->middleware('role:' . App\Models\User::ROLE_ADMIN);
    Route::resource('conductors', ConductorController::class)->middleware('role:' . App\Models\User::ROLE_ADMIN);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/charter', [ReportController::class, 'charter'])->name('reports.charter');
    Route::get('/reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy');
    Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom');
    Route::post('/reports/custom', [ReportController::class, 'generateCustom'])->name('reports.custom.generate');
    Route::get('/reports/custom/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.custom.export.pdf');
    Route::get('/reports/custom/export/excel', [ReportController::class, 'exportExcel'])->name('reports.custom.export.excel');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
