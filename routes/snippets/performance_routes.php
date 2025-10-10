<?php

use App\Http\Controllers\Performance\AssignmentController;
use App\Http\Controllers\Performance\EvaluationController;

Route::middleware(['auth'])->group(function () {
    // Assignment
    Route::get('/performance/assign', [AssignmentController::class, 'index'])->name('performance.assign.index')
        ->middleware('permission:skt.assign_evaluator');
    Route::post('/performance/assign', [AssignmentController::class, 'store'])->name('performance.assign.store')
        ->middleware('permission:skt.assign_evaluator');

    // Evaluation (PYD)
    Route::get('/performance/evaluation', [EvaluationController::class, 'index'])->name('performance.evaluation.index');
    Route::get('/performance/evaluation/create', [EvaluationController::class, 'create'])->name('performance.evaluation.create');
    Route::post('/performance/evaluation', [EvaluationController::class, 'store'])->name('performance.evaluation.store');
    Route::get('/performance/evaluation/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('performance.evaluation.edit');
    Route::put('/performance/evaluation/{evaluation}', [EvaluationController::class, 'update'])->name('performance.evaluation.update');
    Route::get('/performance/evaluation/{evaluation}/submit', [EvaluationController::class, 'submit'])->name('performance.evaluation.submit');
});
