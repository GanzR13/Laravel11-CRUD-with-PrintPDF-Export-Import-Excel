<?php

use App\Http\Controllers\DataKelasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('datakelas4b', DataKelasController::class);
Route::get('datakelasexport', [DataKelasController::class, 'export'])->name('datakelas4b.export');
Route::post('datakelasimport', [DataKelasController::class, 'import'])->name('datakelas4b.import');
Route::get('printpdf', [DataKelasController::class, 'printpdf'])->name('datakelas.printpdf');