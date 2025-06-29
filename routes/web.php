<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataKelasController;


Route::get('/', [DataKelasController::class, 'index'])->name('home');

Route::get('datakelas4b/export', [DataKelasController::class, 'export'])->name('datakelas4b.export');
Route::post('datakelas4b/import', [DataKelasController::class, 'import'])->name('datakelas4b.import');
Route::get('datakelas4b/print-pdf', [DataKelasController::class, 'printpdf'])->name('datakelas4b.printpdf');

Route::resource('datakelas4b', DataKelasController::class);
