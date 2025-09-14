<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\SearchController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::prefix('')->name('frontend.')->group(function () {
    Route::get('/', function () {
        return view('frontend.home');
    })->name('home');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::prefix('booking')->group(function () {
        Route::get('/', [App\Http\Controllers\Frontend\BookingController::class, 'index'])->name('booking.index');
        Route::get('/{id}', [App\Http\Controllers\Frontend\BookingController::class, 'show'])->name('booking.show');
        
        Route::get('/confirmation', function () {
            return view('frontend.booking.confirmation');
        })->name('booking.confirmation');
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
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
require __DIR__.'/admin.php';

require __DIR__.'/auth.php';
