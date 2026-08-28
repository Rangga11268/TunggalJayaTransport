<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RouteController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\BookingController;
// use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\CharterController;
use App\Http\Controllers\PaymentController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });
});

Route::get('/routes/origins-destinations', [RouteController::class, 'originsDestinations']);
Route::apiResource('routes', RouteController::class)->only(['index', 'show']);

Route::get('/buses', function () {
    return response()->json(['data' => \App\Models\Bus::where('status', 'active')->get()]);
});

Route::get('/promos', [\App\Http\Controllers\API\PromoCodeController::class, 'index']);

Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/bookings/select-seats', [BookingController::class, 'selectSeats']);
    Route::post('/bookings/process-payment', [BookingController::class, 'processPayment']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);

    // Route::post('/midtrans/token', [MidtransController::class, 'getToken']);
    // Route::post('/midtrans/update-status', [MidtransController::class, 'updateStatus']);

    // Pariwisata (Charter)
    Route::get('/charter/history', [CharterController::class, 'index']);
    Route::post('/charter/request', [CharterController::class, 'store']);
    Route::post('/charter/{id}/cancel', [CharterController::class, 'cancel']);

    // Notifikasi
    Route::get('/notifications', [\App\Http\Controllers\API\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\API\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [\App\Http\Controllers\API\NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [\App\Http\Controllers\API\NotificationController::class, 'unreadCount']);
});

Route::get('/notifications', [\App\Http\Controllers\API\NotificationController::class, 'index']);
Route::get('/notifications/unread-count', [\App\Http\Controllers\API\NotificationController::class, 'unreadCount']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']);

Route::post('/payments/webhook', [PaymentController::class, 'webhook']);
