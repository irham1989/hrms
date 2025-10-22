<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\Branch\AdminBranchController;
use App\Http\Controllers\Admin\Setting\AdminPublicHolidayController;
use App\Http\Controllers\Admin\Setting\AdminStateWeekendHolidayController;
use App\Http\Controllers\Admin\Setting\GradeSettingController;
use App\Http\Controllers\Admin\Setting\PositionSettingController;
use App\Http\Controllers\Reporting\ReportingController;
use App\Http\Controllers\Admin\StaffStatsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| URL asas: /admin/...
| Nama route: admin.*
|
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Wajib aktif (akaun aktif dsb.)
        Route::middleware('activeuser')->group(function () {

            /*
            |------------------------
            | Dashboard Pentadbir
            |------------------------
            */
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            /*
            |------------------------
            | Maklumat Kakitangan (Statistik)
            |------------------------
            | TIP: boleh tambah middleware role jika perlu
            | ->middleware('role:admin|super-admin')
            */
            Route::get('/staff-stats', [StaffStatsController::class, 'index'])->name('staff.stats');
            Route::get('/staff-stats/filter', [StaffStatsController::class, 'filterStaff'])->name('staff.stats.filter');


            /*
            |------------------------
            | Pengguna
            |------------------------
            */
            Route::prefix('user')->name('user.')->group(function () {
                Route::get('/',                [AdminUserController::class, 'index'])->name('list');
                Route::post('/user-list',      [AdminUserController::class, 'userList'])->name('list.data');
                Route::post('/store-update',   [AdminUserController::class, 'storeUpdateUser'])->name('store_update');
                Route::post('/get-info',       [AdminUserController::class, 'getInfoUser'])->name('get_info');
                Route::post('/user-active',    [AdminUserController::class, 'userActive'])->name('active');
            });

            /*
            |------------------------
            | Penempatan (Cawangan & Jawatan Cawangan)
            |------------------------
            */
            Route::prefix('branch')->name('branch.')->group(function () {
                Route::get('/',                        [AdminBranchController::class, 'index'])->name('index');
                Route::post('/branch-list',            [AdminBranchController::class, 'branchList'])->name('list');
                Route::post('/store-update',           [AdminBranchController::class, 'storeUpdate'])->name('store_update');
                Route::post('/delete',                 [AdminBranchController::class, 'deleteBranch'])->name('delete');

                Route::get('/{branch_id}/{page}',      [AdminBranchController::class, 'branchDetails'])->name('details');
                Route::post('/position-list',          [AdminBranchController::class, 'positionList'])->name('position.list');
                Route::post('/position-store-update',  [AdminBranchController::class, 'positionStoreUpdate'])->name('position.store_update');
                Route::post('/position-get-info',      [AdminBranchController::class, 'positionGetInfo'])->name('position.get_info');
                Route::post('/position-delete',        [AdminBranchController::class, 'positionDelete'])->name('position.delete');
            });

            /*
            |------------------------
            | Laporan (Sejarah Jawatan)
            |------------------------
            */
            Route::prefix('reporting')->name('reporting.')->group(function () {
                // Paparan + carian
                Route::match(['get','post'], '/', [ReportingController::class, 'index'])->name('index');

                // Eksport
                Route::get('/pdf',    [ReportingController::class, 'pdf_download'])->name('pdf');
                Route::post('/excel', [ReportingController::class, 'excelDownloadRaw'])->name('excel');
            });

            /*
            |------------------------
            | Tetapan
            |------------------------
            */
            Route::prefix('setting')->name('setting.')->group(function () {

                // Cuti Umum
                Route::prefix('public-holiday')->name('publicholiday.')->group(function () {
                    Route::get('/',      [AdminPublicHolidayController::class, 'index'])->name('index');
                    Route::post('/list', [AdminPublicHolidayController::class, 'list'])->name('list');
                });

                // Cuti Hujung Minggu Negeri
                Route::prefix('weekend-holiday')->name('weekendholiday.')->group(function () {
                    Route::get('/',          [AdminStateWeekendHolidayController::class, 'index'])->name('index');
                    Route::post('/list',     [AdminStateWeekendHolidayController::class, 'list'])->name('list');
                    Route::post('/store-update', [AdminStateWeekendHolidayController::class, 'storeUpdate'])->name('store_update');
                    Route::post('/get-info',     [AdminStateWeekendHolidayController::class, 'getWeekendHoliday'])->name('get_info');
                    Route::post('/delete',       [AdminStateWeekendHolidayController::class, 'deleteWeekendHoliday'])->name('delete');
                });

                // Tetapan Jawatan
                Route::prefix('position')->name('position.')->group(function () {
                    Route::get('/',      [PositionSettingController::class, 'index'])->name('index');
                    Route::post('/list', [PositionSettingController::class, 'list'])->name('list');
                    Route::post('/store-update', [PositionSettingController::class, 'storeUpdate'])->name('store_update');
                    Route::post('/get-info',     [PositionSettingController::class, 'getPosition'])->name('get_info');
                    Route::post('/delete',       [PositionSettingController::class, 'deletePosition'])->name('delete');
                });

                // Tetapan Gred
                Route::prefix('grade')->name('grade.')->group(function () {
                    Route::get('/',      [GradeSettingController::class, 'index'])->name('index');
                    Route::post('/list', [GradeSettingController::class, 'list'])->name('list');
                    Route::post('/store-update', [GradeSettingController::class, 'storeUpdate'])->name('store_update');
                    Route::post('/get-info',     [GradeSettingController::class, 'getGrade'])->name('get_info');
                    Route::post('/delete',       [GradeSettingController::class, 'deleteGrade'])->name('delete');
                });
            });

        });
    });
