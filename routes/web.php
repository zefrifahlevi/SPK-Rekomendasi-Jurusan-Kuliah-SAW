<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruBkController;
use App\Http\Controllers\SiswaController;
use App\Http\Middleware\RoleMiddleware;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guru BK Admin Routes
Route::middleware(['auth', RoleMiddleware::class . ':guru_bk'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruBkController::class, 'dashboard'])->name('dashboard');

    // Siswa Management
    Route::get('/siswa', [GuruBkController::class, 'indexSiswa'])->name('siswa.index');
    Route::post('/siswa', [GuruBkController::class, 'storeSiswa'])->name('siswa.store');
    Route::put('/siswa/{id}', [GuruBkController::class, 'updateSiswa'])->name('siswa.update');
    Route::delete('/siswa/{id}', [GuruBkController::class, 'destroySiswa'])->name('siswa.destroy');

    // Input Nilai Rapor
    Route::get('/siswa/{id}/rapor', [GuruBkController::class, 'inputNilaiRapor'])->name('rapor.input');
    Route::post('/siswa/{id}/rapor', [GuruBkController::class, 'storeNilaiRapor'])->name('rapor.store');

    // Pertanyaan Kuesioner
    Route::get('/pertanyaan', [GuruBkController::class, 'indexPertanyaan'])->name('pertanyaan.index');
    Route::post('/pertanyaan/kecerdasan', [GuruBkController::class, 'storePertanyaanKecerdasan'])->name('pertanyaan.kecerdasan.store');
    Route::delete('/pertanyaan/kecerdasan/{id}', [GuruBkController::class, 'destroyPertanyaanKecerdasan'])->name('pertanyaan.kecerdasan.destroy');
    Route::post('/pertanyaan/minat', [GuruBkController::class, 'storePertanyaanMinat'])->name('pertanyaan.minat.store');
    Route::delete('/pertanyaan/minat/{id}', [GuruBkController::class, 'destroyPertanyaanMinat'])->name('pertanyaan.minat.destroy');

    // Kriteria & Bobot SAW
    Route::get('/kriteria', [GuruBkController::class, 'indexKriteria'])->name('kriteria.index');
    Route::put('/kriteria', [GuruBkController::class, 'updateKriteria'])->name('kriteria.update');

    // Master Jurusan
    Route::get('/jurusan', [GuruBkController::class, 'indexJurusan'])->name('jurusan.index');
    Route::post('/jurusan', [GuruBkController::class, 'storeJurusan'])->name('jurusan.store');
    Route::put('/jurusan/{id}', [GuruBkController::class, 'updateJurusan'])->name('jurusan.update');
    Route::delete('/jurusan/{id}', [GuruBkController::class, 'destroyJurusan'])->name('jurusan.destroy');

    // Process & Audit SAW
    Route::get('/saw', [GuruBkController::class, 'indexProsesSaw'])->name('saw.index');
    Route::post('/saw/recalculate-all', [GuruBkController::class, 'hitungUlangSawAll'])->name('saw.recalculate');
    Route::get('/saw/detail/{id}', [GuruBkController::class, 'detailSawSiswa'])->name('saw.detail');
});

// Siswa Routes
Route::middleware(['auth', RoleMiddleware::class . ':siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');

    // Pertanyaan Kuesioner Siswa
    Route::get('/kecerdasan', [SiswaController::class, 'formKecerdasan'])->name('kecerdasan.form');
    Route::post('/kecerdasan', [SiswaController::class, 'storeKecerdasan'])->name('kecerdasan.store');

    Route::get('/minat', [SiswaController::class, 'formMinat'])->name('minat.form');
    Route::post('/minat', [SiswaController::class, 'storeMinat'])->name('minat.store');

    // Hasil Rekomendasi
    Route::get('/hasil', [SiswaController::class, 'hasilRekomendasi'])->name('hasil');
});
