<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\StaffHomeController; // ⬅️ NEW
use App\Models\PublicHoliday;
use App\Models\State;
use Holiday\MalaysiaHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;                 // ⬅️ NEW
use Illuminate\Support\Facades\Auth;                // ⬅️ NEW

Route::get('user-inactive', function(){
    return view('user-inactive');
})->name('user-inactive');

Route::match(['GET', 'POST'], '/meta-test', function (Request $request) {
    Log::info('Incoming Request:', $request->all());
    return response()->json(['status' => 'success'], 200);
});

Route::get('/sql-to-excel', [ProfileController::class, 'sqlToExcel']);

Route::match(['GET', 'POST'], '/meta-test-verify', function (Request $request) {
    Log::info('Incoming Request:', $request->all());
    if ($request->hub_challenge) {
        echo $request->hub_challenge;
    }
    return response()->json(['status' => 'success'], 200);
});

/**
 * Dashboard smart-redirect:
 * - super-admin/admin  -> admin.dashboard
 * - selain itu         -> staff.launcher
 */
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user && ($user->hasRole('super-admin') || $user->hasRole('admin'))) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('staff.launcher');
})->middleware(['auth', 'verified', 'activeuser'])->name('dashboard');

// Launcher utama staf (grid tile)
Route::middleware(['auth', 'activeuser'])->group(function () {
    Route::get('/home', [StaffHomeController::class, 'index'])->name('staff.launcher');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/staff.php';
require __DIR__.'/admin.php';
require __DIR__.'/approval-admin.php';
