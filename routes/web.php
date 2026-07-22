<?php

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
    Route::get('laporan/download-pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.download-pdf')->withoutMiddleware([AppHttpMiddlewarensureUserIsKader::class, 'auth']);

    // Data Dasawisma (Admin: CRUD penuh)
    Route::get('dasawisma', [DasawismaController::class, 'index'])->name('dasawisma.index');
    Route::get('dasawisma/{dasawisma}', [DasawismaController::class, 'show'])->name('dasawisma.show');
    Route::post('dasawisma', [DasawismaController::class, 'store'])->name('dasawisma.store');
    Route::put('dasawisma/{dasawisma}', [DasawismaController::class, 'update'])->name('dasawisma.update');
    Route::delete('dasawisma/{dasawisma}', [DasawismaController::class, 'destroy'])->name('dasawisma.destroy');

});

// Kader routes
Route::middleware(['auth', 'verified', 'kader'])->prefix('kader')->name('kader.')->group(function () {
    Route::get('dashboard', [KaderDashboardController::class, 'index'])->name('dashboard');

    // Data Dasawisma (Kader: hanya lihat)
    Route::get('dasawisma', [DasawismaController::class, 'index'])->name('dasawisma.index');
    Route::get('dasawisma/{dasawisma}', [DasawismaController::class, 'show'])->name('dasawisma.show');

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
    Route::get('anggota/{anggotaKeluarga}/edit', [AnggotaKeluargaController::class, 'edit'])->name('anggota.edit');
    Route::put('anggota/{anggotaKeluarga}', [AnggotaKeluargaController::class, 'update'])->name('anggota.update');
    Route::delete('anggota/{anggotaKeluarga}', [AnggotaKeluargaController::class, 'destroy'])->name('anggota.destroy');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/download-pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.download-pdf')->withoutMiddleware([AppHttpMiddlewarensureUserIsKader::class, 'auth']);

});

// Kades routes
Route::middleware(['auth', 'verified', 'kades'])->prefix('kades')->name('kades.')->group(function () {
    Route::get('dashboard', [KadesDashboardController::class, 'index'])->name('dashboard');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/download-pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.download-pdf')->withoutMiddleware([AppHttpMiddlewarensureUserIsKader::class, 'auth']);
    
});

require __DIR__.'/settings.php';
Route::get('test-download-pdf', function() {
    $keluargas = \App\Models\Keluarga::whereHas('verifikasi', function ($q) {
        $q->where('status_verifikasi', 'disetujui');
    })->with(['dasawisma', 'anggotaKeluargas', 'verifikasi.admin.user'])->take(5)->get();
    $stats = ['total_kk'=>0, 'total_warga'=>0, 'balita_laki'=>0, 'balita_perempuan'=>0, 'lansia'=>0, 'laki_laki'=>0, 'perempuan'=>0];
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', compact('keluargas', 'stats'));
    return $pdf->download('test.pdf');
});
