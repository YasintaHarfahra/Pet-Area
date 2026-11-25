<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dokter\RiwayatController;
use App\Http\Controllers\ReminderController;

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ============================================================
// AUTH ROUTES
// ============================================================
Route::get("/", fn() => redirect()->route("login"));
Route::get("/login", [AuthController::class, "showLoginForm"])->name("login");
Route::post("/login", [AuthController::class, "login"])->name("login.post");
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

// FORGOT PASSWORD DOKTER
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'sendForgotPassword'])->name('forgot.password.send');


// Home redirect based on role (after login)
Route::get("/home", function() {
    if (Auth::guard("admin")->check()) {
        return redirect()->route("admin.dashboard");
    } elseif (Auth::guard("dokter")->check()) {
        return redirect()->route("dokter.dashboard");
    }
    return redirect()->route("login");
})->name("home");

// ============================================================
// DOKTER ROUTES
// ============================================================
Route::prefix("dokter")
    ->name("dokter.")
    ->middleware(["guard-switch:dokter", "auth:dokter"])
    ->group(function () {
        
        // Dashboard (default route for dokter)
        Route::get("/", fn() => redirect()->route("dokter.dashboard"));
        Route::get("/dashboard", [App\Http\Controllers\Dokter\DashboardController::class, "index"])
            ->name("dashboard");
        
        // ==========================================
        // ANTRIAN PEMERIKSAAN
        // ==========================================
        Route::prefix("antrian")->name("antrian.")->group(function () {
            Route::get("/", [App\Http\Controllers\Dokter\AntrianController::class, "index"])
                ->name("index");
            Route::get("/{queue}", [App\Http\Controllers\Dokter\AntrianController::class, "show"])
                ->name("show");
            Route::post("/{queue}/start", [App\Http\Controllers\Dokter\AntrianController::class, "startExamination"])
                ->name("start");
            Route::post("/{queue}/skip", [App\Http\Controllers\Dokter\AntrianController::class, "skip"])
                ->name("skip");
        });
        
        // ==========================================
        // PEMERIKSAAN (Medical Records)
        // ==========================================
        Route::prefix("pemeriksaan")->name("examinations.")->group(function () {
            Route::get('/create', [App\Http\Controllers\Dokter\ExaminationController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Dokter\ExaminationController::class, 'store'])->name('store');
        });
        
        // ==========================================
        // RIWAYAT PASIEN (Read-only)
        // ==========================================
        // ==========================================
        // RIWAYAT PASIEN (Read-only, clean & urut)
        // ==========================================
        Route::prefix("riwayat")->name("riwayat.")->group(function () {
            // List semua riwayat pasien
            Route::get("/", [App\Http\Controllers\Dokter\RiwayatController::class, "index"])
                ->name("index");
            // Export riwayat
            Route::get("/export", [App\Http\Controllers\Dokter\RiwayatController::class, "export"])
                ->name("export");
            // Edit riwayat rekam medis
            Route::get("/record/{record}/edit", [App\Http\Controllers\Dokter\RiwayatController::class, "edit"])
                ->name("edit");
            Route::put("/record/{record}", [App\Http\Controllers\Dokter\RiwayatController::class, "update"])
                ->name("update");
            // Detail rekam medis tertentu (by record id)
            Route::get("/record/{record}", [App\Http\Controllers\Dokter\RiwayatController::class, "show"])
                ->name("record");
            // Hapus rekam medis
            Route::delete("/record/{record}", [App\Http\Controllers\Dokter\RiwayatController::class, "destroy"])
                ->name("destroy");
            // Detail riwayat per pasien (by pet id)
            Route::get("/{pet}", [App\Http\Controllers\Dokter\RiwayatController::class, "showByPet"])
                ->name("show");
        });
        
        // ==========================================
        // LAPORAN MEDIS
        // ==========================================
        Route::prefix("laporan")->name("laporan.")->group(function () {
            Route::get("/", [App\Http\Controllers\Dokter\LaporanController::class, "index"])
                ->name("index");
            Route::get("/excel", [App\Http\Controllers\Dokter\LaporanController::class, "exportExcel"])
                ->name("excel");
            Route::get("/pdf", [App\Http\Controllers\Dokter\LaporanController::class, "exportPdf"])
                ->name("pdf");
        });
        
        // ==========================================
        // KELOLA STAFF
        // ==========================================
        Route::prefix("staf")->name("staff.")->group(function () {
            Route::get("/", [App\Http\Controllers\Dokter\StaffController::class, "index"])
                ->name("index");
            Route::get("/create", [App\Http\Controllers\Dokter\StaffController::class, "create"])
                ->name("create");
            Route::post("/", [App\Http\Controllers\Dokter\StaffController::class, "store"])
                ->name("store");
            Route::get("/{staff}/edit", [App\Http\Controllers\Dokter\StaffController::class, "edit"])
                ->name("edit");
            Route::put("/{staff}", [App\Http\Controllers\Dokter\StaffController::class, "update"])
                ->name("update");
            Route::delete("/{staff}", [App\Http\Controllers\Dokter\StaffController::class, "destroy"])
                ->name("destroy");
        });
        
        // ==========================================
        // GANTI PASSWORD
        // ==========================================
        Route::get("/password", [App\Http\Controllers\Dokter\PasswordController::class, "edit"])
            ->name("password.edit");
        Route::put("/password", [App\Http\Controllers\Dokter\PasswordController::class, "update"])
            ->name("password.update");
        
        // ==========================================
        // API untuk Dokter
        // ==========================================
        Route::prefix("api")->group(function () {
            Route::get("/pets/search", [App\Http\Controllers\Dokter\ExaminationController::class, "searchPets"])
                ->name("api.pets.search");
            Route::get("/medicines", [App\Http\Controllers\Dokter\ExaminationController::class, "getMedicines"])
                ->name("api.medicines");
            Route::post("/treatments", [App\Http\Controllers\Dokter\TreatmentController::class, "storeAjax"])
                ->name("api.treatments.store");
            Route::delete("/treatments", [App\Http\Controllers\Dokter\TreatmentController::class, "destroyAjax"])
                ->name("api.treatments.destroy");
            Route::get("/antrian/poll", [App\Http\Controllers\Dokter\AntrianController::class, "pollData"])
                ->name("api.antrian.poll");
        });
    });

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::prefix("admin")
    ->name("admin.")
    ->middleware(["guard-switch:admin", "auth:admin"])
    ->group(function () {
        
        // Dashboard
        Route::get("/", fn() => redirect()->route("admin.dashboard"));
        Route::get("/dashboard", [App\Http\Controllers\Admin\DashboardController::class, "index"])
            ->name("dashboard");
        
        // TOMBOL REMINDER WA (Posisinya di dalam prefix admin)
        // Jalur ini akan menjadi: /admin/reminder/kirim
        Route::post('/reminder/kirim', [ReminderController::class, 'kirimManual'])->name('reminder.kirim');
        
        // ==========================================
        // PENDAFTARAN (Owner & Pet)
        // ==========================================
        Route::prefix("pendaftaran")->name("registration.")->group(function () {
            
            // Owners
            Route::prefix("pemilik")->name("owners.")->group(function () {
                Route::get("/", [App\Http\Controllers\Admin\RegistrationController::class, "indexOwners"])
                    ->name("index");
                Route::get("/create", [App\Http\Controllers\Admin\RegistrationController::class, "createOwner"])
                    ->name("create");
                Route::post("/", [App\Http\Controllers\Admin\RegistrationController::class, "storeOwner"])
                    ->name("store");
                Route::get("/{owner}", [App\Http\Controllers\Admin\RegistrationController::class, "showOwner"])
                    ->name("show");
                Route::get("/{owner}/edit", [App\Http\Controllers\Admin\RegistrationController::class, "editOwner"])
                    ->name("edit");
                Route::put("/{owner}", [App\Http\Controllers\Admin\RegistrationController::class, "updateOwner"])
                    ->name("update");
                Route::delete("/{owner}", [App\Http\Controllers\Admin\RegistrationController::class, "destroyOwner"])
                    ->name("destroy");
                
                // Tambah hewan untuk owner
                Route::get("/{owner}/hewan/create", [App\Http\Controllers\Admin\RegistrationController::class, "createPet"])
                    ->name("pets.create");
                Route::post("/{owner}/hewan", [App\Http\Controllers\Admin\RegistrationController::class, "storePet"])
                    ->name("pets.store");
            });
            
            // Pets (edit/delete)
            Route::prefix("hewan")->name("pets.")->group(function () {
                Route::get("/{pet}/edit", [App\Http\Controllers\Admin\RegistrationController::class, "editPet"])
                    ->name("edit");
                Route::put("/{pet}", [App\Http\Controllers\Admin\RegistrationController::class, "updatePet"])
                    ->name("update");
                Route::delete("/{pet}", [App\Http\Controllers\Admin\RegistrationController::class, "destroyPet"])
                    ->name("destroy");
            });
            
            // API
            Route::get("/api/owners/search", [App\Http\Controllers\Admin\RegistrationController::class, "searchOwners"])
                ->name("api.owners.search");
        });
        
        // ==========================================
        // KASIR (Pembayaran)
        // ==========================================
        Route::prefix("kasir")->name("cashier.")->group(function () {
            Route::get("/", [App\Http\Controllers\Admin\CashierController::class, "index"])
                ->name("index");
            Route::get("/laporan-periode", [App\Http\Controllers\Admin\CashierController::class, "periodReport"])
                ->name("period-report");
            Route::get("/laporan-periode/pdf", [App\Http\Controllers\Admin\CashierController::class, "periodReportPdf"])
                ->name("period-report-pdf");
            // Backward compatibility
            Route::get("/laporan-harian", [App\Http\Controllers\Admin\CashierController::class, "periodReport"])
                ->name("daily-report");
            Route::get("/laporan-harian/pdf", [App\Http\Controllers\Admin\CashierController::class, "periodReportPdf"])
                ->name("daily-report-pdf");
            Route::get("/{transaction}", [App\Http\Controllers\Admin\CashierController::class, "show"])
                ->name("show");
            Route::post("/{transaction}/bayar", [App\Http\Controllers\Admin\CashierController::class, "processPayment"])
                ->name("pay");
            Route::get("/{transaction}/struk", [App\Http\Controllers\Admin\CashierController::class, "receipt"])
                ->name("receipt");
            Route::delete("/{transaction}", [App\Http\Controllers\Admin\CashierController::class, "destroy"])
                ->name("destroy");
        });
        
        // ==========================================
        // STOK OBAT
        // ==========================================
        Route::prefix("obat")->name("medicines.")->group(function () {
            Route::get("/", [App\Http\Controllers\Admin\MedicineController::class, "index"])
                ->name("index");
            Route::get("/create", [App\Http\Controllers\Admin\MedicineController::class, "create"])
                ->name("create");
            Route::post("/", [App\Http\Controllers\Admin\MedicineController::class, "store"])
                ->name("store");
            Route::get("/{medicine}/edit", [App\Http\Controllers\Admin\MedicineController::class, "edit"])
                ->name("edit");
            Route::put("/{medicine}", [App\Http\Controllers\Admin\MedicineController::class, "update"])
                ->name("update");
            Route::post("/{medicine}/tambah-stok", [App\Http\Controllers\Admin\MedicineController::class, "addStock"])
                ->name("add-stock");
            Route::delete("/{medicine}", [App\Http\Controllers\Admin\MedicineController::class, "destroy"])
                ->name("destroy");
        });
        
        // ==========================================
        // SURAT RUJUKAN
        // ==========================================
        Route::prefix("rujukan")->name("referrals.")->group(function () {
            Route::get("/", [App\Http\Controllers\Admin\ReferralController::class, "index"])
                ->name("index");
            Route::get("/{referral}", [App\Http\Controllers\Admin\ReferralController::class, "show"])
                ->name("show");
            Route::get("/{referral}/print", [App\Http\Controllers\Admin\ReferralController::class, "print"])
                ->name("print");
        });

        // ==========================================
        // ANTRIAN PASIEN
        // ==========================================
        Route::prefix("antrian")->name("queues.")->group(function () {
            Route::get("/", [App\Http\Controllers\Admin\QueueController::class, "index"])
                ->name("index");
            Route::get("/create", [App\Http\Controllers\Admin\QueueController::class, "create"])
                ->name("create");
            Route::post("/", [App\Http\Controllers\Admin\QueueController::class, "store"])
                ->name("store");
            Route::get("/{queue}", [App\Http\Controllers\Admin\QueueController::class, "show"])
                ->name("show");
            Route::post("/{queue}/cancel", [App\Http\Controllers\Admin\QueueController::class, "cancel"])
                ->name("cancel");
            Route::get("/api/pets/search", [App\Http\Controllers\Admin\QueueController::class, "searchPets"])
                ->name("api.pets.search");
        });
        
        // ==========================================
        // API untuk Admin
        // ==========================================
        Route::prefix("api")->group(function () {
            Route::get("/medicines/search", [App\Http\Controllers\Admin\MedicineController::class, "apiSearch"])
                ->name("api.medicines.search");
            Route::get("/queues/poll", [App\Http\Controllers\Admin\QueueController::class, "pollData"])
                ->name("api.queues.poll");
        });
    });
    
