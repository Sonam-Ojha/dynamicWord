<?php

use App\Http\Controllers\DocumentGenerationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->hasAnyRole(['Super Admin', 'Admin'])) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('generate.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('generate')->name('generate.')->group(function () {
        Route::get('/', [DocumentGenerationController::class, 'index'])->name('index');
        Route::get('/banks', [DocumentGenerationController::class, 'selectBank'])->name('banks');
        Route::get('/banks/{bank}/templates', [DocumentGenerationController::class, 'selectTemplate'])->name('templates');
        Route::get('/templates/{template}/form', [DocumentGenerationController::class, 'showForm'])->name('form');
        Route::post('/templates/{template}/generate', [DocumentGenerationController::class, 'generate'])->name('store');
        Route::get('/documents/{document}/preview', [DocumentGenerationController::class, 'preview'])->name('preview');
        Route::post('/documents/{document}/finalize', [DocumentGenerationController::class, 'finalize'])->name('finalize');
        Route::get('/documents/{document}/print', [DocumentGenerationController::class, 'print'])->name('print');
        Route::get('/documents/{document}/download', [DocumentGenerationController::class, 'download'])->name('download');
    });
});

require __DIR__.'/auth.php';
