<?php

use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CalculationController::class, 'index'])->name('home');
Route::post('/calculate', [CalculationController::class, 'calculate'])->name('calculate');
Route::get('/history', [CalculationController::class, 'history'])->name('history');
Route::delete('/history/{id}', [CalculationController::class, 'destroy'])->name('destroy');
Route::get('/recalculate/{id}', [CalculationController::class, 'recalculate'])->name('recalculate');
Route::get('/results/{id}', [CalculationController::class, 'show'])->name('results');
