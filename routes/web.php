<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::prefix('')->name('frontend.')->group(function () {
    Route::get('/', function () {
        return view('frontend.home');
    })->name('home');

    Route::prefix('booking')->group(function () {
        Route::get('/', function () {
            return view('frontend.booking.index');
        })->name('booking.index');
        
        Route::get('/confirmation', function () {
            return view('frontend.booking.confirmation');
        })->name('booking.confirmation');
    });

    Route::get('/routes', function () {
        return view('frontend.routes.index');
    })->name('routes.index');

    Route::get('/fleet', function () {
        return view('frontend.fleet.index');
    })->name('fleet.index');

    Route::get('/news', function () {
        return view('frontend.news.index');
    })->name('news.index');

    Route::get('/about', function () {
        return view('frontend.about.index');
    })->name('about');

    Route::get('/contact', function () {
        return view('frontend.contact.index');
    })->name('contact');
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
