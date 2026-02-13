<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LearningLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/logs/trashed', [LearningLogController::class, 'trashed'])->name('logs.trashed');
    Route::patch('/logs/{id}/restore', [LearningLogController::class, 'restore'])->name('logs.restore');

    Route::resource('logs', LearningLogController::class);
});

require __DIR__.'/auth.php';
