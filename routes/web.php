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

    Route::get('spm/file/{id}', [\App\Http\Controllers\SpmController::class, 'streamFile'])->name('spm.file');
    Route::get('spm/{spm}/print-receipt', [\App\Http\Controllers\SpmController::class, 'printReceipt'])->name('spm.print-receipt');
    Route::get('spm/{spm}/print', [\App\Http\Controllers\SpmController::class, 'print'])->name('spm.print');
    Route::post('spm/{id}/status', [\App\Http\Controllers\SpmController::class, 'updateStatus'])->name('spm.updateStatus');
    Route::get('spm-export', [\App\Http\Controllers\SpmController::class, 'exportCsv'])->name('spm.export');
    Route::resource('spm', \App\Http\Controllers\SpmController::class);

    Route::post('notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::post('notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read-all');

    Route::get('notifications/fetch', function () {
        return response()->json(auth()->user()->notifications()->take(10)->get());
    })->name('notifications.fetch');
    Route::get('sp2d-export', [\App\Http\Controllers\Sp2dController::class, 'exportCsv'])->name('sp2d.export');
    Route::post('sp2d/{id}/status', [\App\Http\Controllers\Sp2dController::class, 'updateStatus'])->name('sp2d.updateStatus');
    Route::get('sp2d/{id}/print-receipt', [\App\Http\Controllers\Sp2dController::class, 'printReceipt'])->name('sp2d.print-receipt');
    Route::get('sp2d/file/{id}', [\App\Http\Controllers\Sp2dController::class, 'streamFile'])->name('sp2d.file');
    Route::resource('sp2d', \App\Http\Controllers\Sp2dController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('dipas', \App\Http\Controllers\DipaController::class);
    Route::resource('penyedias', \App\Http\Controllers\PenyediaController::class);
    Route::resource('paket-pekerjaans', \App\Http\Controllers\PaketPekerjaanController::class);

    Route::post('basts/{bast}/status', [\App\Http\Controllers\BastController::class, 'updateStatus'])->name('basts.updateStatus');
    Route::get('basts/file/{id}', [\App\Http\Controllers\BastController::class, 'streamFile'])->name('basts.file');
    Route::resource('basts', \App\Http\Controllers\BastController::class);

    // Laporan Analitik (10 Laporan)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/realisasi-pagu', [\App\Http\Controllers\ReportController::class, 'realisasiPagu'])->name('realisasi-pagu');
        Route::get('/waktu-proses', [\App\Http\Controllers\ReportController::class, 'waktuProses'])->name('waktu-proses');
        Route::get('/tren-pencairan', [\App\Http\Controllers\ReportController::class, 'trenPencairan'])->name('tren-pencairan');
        Route::get('/serapan-paket', [\App\Http\Controllers\ReportController::class, 'serapanPaket'])->name('serapan-paket');
        Route::get('/distribusi-penyedia', [\App\Http\Controllers\ReportController::class, 'distribusiPenyedia'])->name('distribusi-penyedia');
        Route::get('/status-dokumen', [\App\Http\Controllers\ReportController::class, 'statusDokumen'])->name('status-dokumen');
        Route::get('/tagihan-outstanding', [\App\Http\Controllers\ReportController::class, 'tagihanOutstanding'])->name('tagihan-outstanding');
        Route::get('/kinerja-ppk', [\App\Http\Controllers\ReportController::class, 'kinerjaPpk'])->name('kinerja-ppk');
        Route::get('/sisa-waktu-kontrak', [\App\Http\Controllers\ReportController::class, 'sisaWaktuKontrak'])->name('sisa-waktu-kontrak');
        Route::get('/komposisi-jenis-spm', [\App\Http\Controllers\ReportController::class, 'komposisiJenisSpm'])->name('komposisi-jenis-spm');
    });
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::resource('satker', \App\Http\Controllers\SatkerController::class);
    Route::resource('ppk', \App\Http\Controllers\PpkController::class);
});

require __DIR__.'/auth.php';
