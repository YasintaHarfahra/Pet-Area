<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\DoctorDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda.
| Rute-rute ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditetapkan ke grup middleware "web". Buat sesuatu yang hebat!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:admin,dokter'])->group(function () {
    Route::get('/home', function() {
        if (Auth::guard('dokter')->check()) {
            return redirect()->route('dashboard.dokter');
        } elseif (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard.admin');
        }
        return redirect()->route('login');
    })->name('home');

    Route::get('/pasien/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/rekam-medis/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('medical_records.show');
});

Route::middleware(['auth:dokter', 'can:access-dokter-area'])->group(function () {
    Route::get('/dashboard-dokter', [DoctorDashboardController::class, 'index'])->name('dashboard.dokter');
    Route::get('/dokter/pasien', [PatientController::class, 'indexDoctor'])->name('dokter.patients.index');
    Route::get('/dokter/pasien/create', [PatientController::class, 'create'])->name('dokter.patients.create');
    Route::post('/dokter/pasien', [PatientController::class, 'store'])->name('dokter.patients.store');
    Route::get('/dokter/antrian', [QueueController::class, 'doctorIndex'])->name('dokter.queues.index');
    Route::get('/dokter/antrian/{queue}', [QueueController::class, 'doctorShow'])->name('dokter.queues.show');
    Route::put('/dokter/antrian/{queue}/status', [QueueController::class, 'updateStatus'])->name('dokter.queues.update-status');
    Route::get('/dokter/rekam-medis', [MedicalRecordController::class, 'indexDoctor'])->name('dokter.medical_records.index');
    Route::get('/dokter/rekam-medis/create', [MedicalRecordController::class, 'create'])->name('dokter.medical_records.create');
    Route::post('/dokter/rekam-medis', [MedicalRecordController::class, 'store'])->name('dokter.medical_records.store');
    Route::get('/dokter/rekam-medis/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('dokter.medical_records.edit');
    Route::put('/dokter/rekam-medis/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('dokter.medical_records.update');
    Route::delete('/dokter/rekam-medis/{medicalRecord}', [MedicalRecordController::class, 'destroy'])->name('dokter.medical_records.destroy');
    Route::get('/dokter/staff', [StaffController::class, 'index'])->name('dokter.staff.index');
    Route::get('/dokter/staff/create', [StaffController::class, 'create'])->name('dokter.staff.create');
    Route::post('/dokter/staff', [StaffController::class, 'store'])->name('dokter.staff.store');
    Route::get('/dokter/staff/{staff}/edit', [StaffController::class, 'edit'])->name('dokter.staff.edit');
    Route::put('/dokter/staff/{staff}', [StaffController::class, 'update'])->name('dokter.staff.update');
    Route::delete('/dokter/staff/{staff}', [StaffController::class, 'destroy'])->name('dokter.staff.destroy');
    Route::get('/dokter/ganti-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('dokter.change_password');
    Route::put('/dokter/ganti-password', [ChangePasswordController::class, 'changePassword']);
    
    // Laporan
    Route::get('/dokter/laporan', [App\Http\Controllers\DoctorReportController::class, 'index'])->name('dokter.reports.index');
    Route::post('/dokter/laporan/export', [App\Http\Controllers\DoctorReportController::class, 'export'])->name('dokter.reports.export');
});

Route::middleware(['auth:admin', 'can:access-admin-area'])->group(function () {
    Route::get('/dashboard-admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/admin/pasien', [PatientController::class, 'indexAdmin'])->name('admin.patients.index');
    Route::get('/admin/pasien/create', [PatientController::class, 'create'])->name('admin.patients.create');
    Route::post('/admin/pasien', [PatientController::class, 'store'])->name('admin.patients.store');
    Route::get('/admin/pasien/{patient}/edit', [PatientController::class, 'edit'])->name('admin.patients.edit');
    Route::put('/admin/pasien/{patient}', [PatientController::class, 'update'])->name('admin.patients.update');
    Route::delete('/admin/pasien/{patient}', [PatientController::class, 'destroy'])->name('admin.patients.destroy');
    Route::get('/admin/rekam-medis', [MedicalRecordController::class, 'indexAdmin'])->name('admin.medical_records.index');
    Route::get('/admin/transaksi', [TransactionController::class, 'index'])->name('admin.transactions.index');
    Route::get('/admin/transaksi/create', [TransactionController::class, 'create'])->name('admin.transactions.create');
    Route::post('/admin/transaksi', [TransactionController::class, 'store'])->name('admin.transactions.store');
    Route::get('/admin/transaksi/{transaction}', [TransactionController::class, 'show'])->name('admin.transactions.show');
    Route::get('/admin/transaksi/{transaction}/edit', [TransactionController::class, 'edit'])->name('admin.transactions.edit');
    Route::put('/admin/transaksi/{transaction}', [TransactionController::class, 'update'])->name('admin.transactions.update');
    Route::delete('/admin/transaksi/{transaction}', [TransactionController::class, 'destroy'])->name('admin.transactions.destroy');
    Route::get('/admin/transaksi/{transaction}/print', [TransactionController::class, 'printNota'])->name('admin.transactions.print_nota');
    Route::get('/admin/kunjungan', [VisitController::class, 'index'])->name('admin.visits.index');
    Route::get('/admin/kunjungan/create', [VisitController::class, 'create'])->name('admin.visits.create');
    Route::post('/admin/kunjungan', [VisitController::class, 'store'])->name('admin.visits.store');
    Route::get('/admin/kunjungan/{visit}', [VisitController::class, 'show'])->name('admin.visits.show');
    Route::get('/admin/kunjungan/{visit}/edit', [VisitController::class, 'edit'])->name('admin.visits.edit');
    Route::put('/admin/kunjungan/{visit}', [VisitController::class, 'update'])->name('admin.visits.update');
    Route::delete('/admin/kunjungan/{visit}', [VisitController::class, 'destroy'])->name('admin.visits.destroy');
    Route::get('/admin/profil', function() { return "Halaman Edit Profil Admin"; })->name('admin.profile.edit');
    Route::get('/admin/antrian', [QueueController::class, 'adminIndex'])->name('admin.queues.index');
    Route::get('/admin/antrian/create', [QueueController::class, 'create'])->name('admin.queues.create');
    Route::post('/admin/antrian', [QueueController::class, 'store'])->name('admin.queues.store');
    Route::get('/admin/antrian/{queue}', [QueueController::class, 'show'])->name('admin.queues.show');
    Route::delete('/admin/antrian/{queue}', [QueueController::class, 'cancel'])->name('admin.queues.cancel');
    Route::get('/admin/antrian/data', [QueueController::class, 'getQueueData'])->name('admin.queues.data');
    Route::get('/admin/antrian/search-patients', [QueueController::class, 'searchPatients'])->name('admin.queues.search-patients');
});
