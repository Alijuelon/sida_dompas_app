<?php

use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kader\DasawismaController;
use App\Http\Controllers\Kader\KeluargaController;
use App\Http\Controllers\Kader\AnggotaKeluargaController;
use App\Http\Controllers\Kader\KaderDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Kades\KadesDashboardController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

// Redirect dashboard berdasarkan role
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

    // Verifikasi
    Route::get('verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('verifikasi/{verifikasi}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::post('verifikasi/{verifikasi}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
    Route::post('verifikasi/{verifikasi}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // AI Assistant
    Route::post('ai-assistant', [AiAssistantController::class, 'chat'])->name('ai.chat');
});

// Kader routes
Route::middleware(['auth', 'verified', 'kader'])->prefix('kader')->name('kader.')->group(function () {
    Route::get('dashboard', [KaderDashboardController::class, 'index'])->name('dashboard');

    // Data Dasawisma
    Route::get('dasawisma', [DasawismaController::class, 'index'])->name('dasawisma.index');
    Route::get('dasawisma/create', [DasawismaController::class, 'create'])->name('dasawisma.create');
    Route::post('dasawisma', [DasawismaController::class, 'store'])->name('dasawisma.store');
    Route::get('dasawisma/{dasawisma}/edit', [DasawismaController::class, 'edit'])->name('dasawisma.edit');
    Route::put('dasawisma/{dasawisma}', [DasawismaController::class, 'update'])->name('dasawisma.update');
    Route::delete('dasawisma/{dasawisma}', [DasawismaController::class, 'destroy'])->name('dasawisma.destroy');

    // Data Keluarga (KK)
    Route::get('keluarga', [KeluargaController::class, 'index'])->name('keluarga.index');
    Route::get('keluarga/create', [KeluargaController::class, 'create'])->name('keluarga.create');
    Route::post('keluarga', [KeluargaController::class, 'store'])->name('keluarga.store');
    Route::get('keluarga/{keluarga}', [KeluargaController::class, 'show'])->name('keluarga.show');
    Route::get('keluarga/{keluarga}/edit', [KeluargaController::class, 'edit'])->name('keluarga.edit');
    Route::put('keluarga/{keluarga}', [KeluargaController::class, 'update'])->name('keluarga.update');
    Route::delete('keluarga/{keluarga}', [KeluargaController::class, 'destroy'])->name('keluarga.destroy');

    // Status Verifikasi
    Route::get('status-verifikasi', [KeluargaController::class, 'statusVerifikasi'])->name('status-verifikasi');

    // Anggota Keluarga
    Route::post('keluarga/{keluarga}/anggota', [AnggotaKeluargaController::class, 'store'])->name('anggota.store');
    Route::put('anggota/{anggotaKeluarga}', [AnggotaKeluargaController::class, 'update'])->name('anggota.update');
    Route::delete('anggota/{anggotaKeluarga}', [AnggotaKeluargaController::class, 'destroy'])->name('anggota.destroy');

    // AI Assistant
    Route::post('ai-assistant', [AiAssistantController::class, 'chat'])->name('ai.chat');
});

// Kades routes
Route::middleware(['auth', 'verified', 'kades'])->prefix('kades')->name('kades.')->group(function () {
    Route::get('dashboard', [KadesDashboardController::class, 'index'])->name('dashboard');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    
    // AI Assistant
    Route::post('ai-assistant', [AiAssistantController::class, 'chat'])->name('ai.chat');
});

require __DIR__.'/settings.php';
