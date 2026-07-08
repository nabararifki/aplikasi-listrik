<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBillController;
use App\Http\Controllers\BillController;
use App\Http\Middleware\CheckAccessLevel;
use Illuminate\Support\Facades\Route;

// Rute Publik (Tanpa Login) untuk pencarian tagihan pelanggan umum
Route::get('/', [PublicBillController::class, 'index'])->name('public.bills.index');
Route::get('/search', [PublicBillController::class, 'search'])->name('public.bills.search');

Route::get('/dashboard', function () {
    // Mengambil statistik agregasi dari database untuk ditampilkan pada infografis dashboard
    $total_customers    = \App\Models\Customer::count();
    $total_bills_count  = \App\Models\Bill::count();
    $total_usage_kwh    = \App\Models\Bill::sum('electricity_usage');

    return view('dashboard', compact('total_customers', 'total_bills_count', 'total_usage_kwh'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Administratif (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute manajemen tagihan listrik dengan kustom middleware CheckAccessLevel (Level 1: Admin, Level 2: Officer)
    Route::middleware([CheckAccessLevel::class . ':1,2'])->group(function () {
        Route::resource('bills', BillController::class);
        Route::patch('/bills/{bill}/toggle-status', [BillController::class, 'toggleStatus'])->name('bills.toggle-status');
        Route::resource('customers', CustomerController::class);
    });
});

require __DIR__.'/auth.php';

