<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $total_spm = \App\Models\Spm::count();
    $total_sp2d = \App\Models\Sp2d::count();
    $nilai_spm = \App\Models\Spm::sum('nilai_spm');
    $nilai_sp2d = \App\Models\Sp2d::sum('nilai_sp2d');

    return view('dashboard', compact('total_spm', 'total_sp2d', 'nilai_spm', 'nilai_sp2d'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('spm', \App\Http\Controllers\SpmController::class);
    Route::resource('sp2d', \App\Http\Controllers\Sp2dController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

require __DIR__.'/auth.php';
