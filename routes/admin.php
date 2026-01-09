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



    // Urusan Bus (Manager jadwal boleh akses)
    Route::resource('buses', BusController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);

    // Cek plat nomor ada ga via AJAX
    Route::get('/buses/check-plate/{plateNumber}', [BusController::class, 'checkPlateNumber'])->name('buses.check-plate');

    // Atur Rute
    Route::resource('routes', RouteController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);

    // Kelola Jadwal
    Route::resource('schedules', ScheduleController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::post('/schedules/{schedule}/create-next-day', [ScheduleController::class, 'createNextDaySchedule'])->name('schedules.create-next-day')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    
    // Dashboard Jadwal
    Route::get('/schedule-management', [ScheduleManagementController::class, 'index'])->name('schedule-management.index')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    Route::get('/schedule-management/{id}', [ScheduleManagementController::class, 'show'])->name('schedule-management.show')->middleware('role:'.App\Models\User::ROLE_ADMIN.','.App\Models\User::ROLE_SCHEDULE_MANAGER);
    
    

    // Data Booking (Khusus admin yang megang duit/tiket)
    Route::resource('bookings', BookingController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Fitur Berita
    Route::resource('news', NewsController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Kategori
    Route::resource('categories', CategoryController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);



    // Kelola User
    Route::resource('users', UserController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Kelola Pelanggan
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index')->middleware('role:'.App\Models\User::ROLE_ADMIN);
    Route::get('/customers/{email}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show')->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Data Supir
    Route::resource('drivers', DriverController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Data Kondektur
    Route::resource('conductors', ConductorController::class)->middleware('role:'.App\Models\User::ROLE_ADMIN);

    // Laporan (Admin & Manager bisa liat)
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