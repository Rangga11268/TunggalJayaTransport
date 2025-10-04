<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::prefix('')->name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/autocomplete-data', [HomeController::class, 'getOriginsAndDestinations'])->name('autocomplete.data');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::prefix('booking')->group(function () {
        Route::get('/', [App\Http\Controllers\Frontend\BookingController::class, 'index'])->name('booking.index');
        Route::get('/schedules', [App\Http\Controllers\Frontend\BookingController::class, 'schedules'])->name('booking.schedules');
        Route::get('/{id}', [App\Http\Controllers\Frontend\BookingController::class, 'show'])->name('booking.show');
        Route::post('/', [App\Http\Controllers\Frontend\BookingController::class, 'store'])->middleware('auth')->name('booking.store');
        Route::post('/select-seats', [App\Http\Controllers\Frontend\BookingController::class, 'selectSeats'])->middleware('auth')->name('booking.select-seats');
        Route::post('/process-payment', [App\Http\Controllers\Frontend\BookingController::class, 'processPayment'])->middleware('auth')->name('booking.process-payment');
        Route::post('/check-availability', [App\Http\Controllers\Frontend\BookingController::class, 'checkAvailability'])->name('check-availability');
        
        Route::get('/confirmation/{booking}', [App\Http\Controllers\Frontend\BookingController::class, 'confirmation'])->name('booking.confirmation');
        Route::get('/success/{id}', [App\Http\Controllers\Frontend\BookingController::class, 'success'])->name('booking.success');
        Route::get('/ticket/{booking}', [App\Http\Controllers\Frontend\BookingController::class, 'downloadTicket'])->name('booking.download-ticket');
        Route::get('/ticket/view/{booking}', [App\Http\Controllers\Frontend\BookingController::class, 'viewTicket'])->name('booking.view-ticket');
    });

    Route::get('/routes', [App\Http\Controllers\Frontend\RouteController::class, 'index'])->name('routes.index');
    Route::get('/routes/{id}', [App\Http\Controllers\Frontend\RouteController::class, 'show'])->name('routes.show');

    Route::get('/fleet', [App\Http\Controllers\Frontend\FleetController::class, 'index'])->name('fleet.index');

    Route::get('/news', [App\Http\Controllers\Frontend\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [App\Http\Controllers\Frontend\NewsController::class, 'show'])->name('news.show');

    Route::get('/about', function () {
        return view('frontend.about.index');
    })->name('about');

    Route::get('/contact', function () {
        return view('frontend.contact.index');
    })->name('contact');
    
    Route::post('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'store'])->name('contact.store');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Booking History Routes
    Route::prefix('booking-history')->name('booking-history.')->group(function () {
        Route::get('/', [App\Http\Controllers\BookingHistoryController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\BookingHistoryController::class, 'show'])->name('show');
    });
    

});

// Admin Routes
require __DIR__.'/admin.php';

require __DIR__.'/auth.php';
