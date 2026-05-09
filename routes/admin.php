<?php

use App\Http\Controllers\Admin\BankBranchController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadLogController;
use App\Http\Controllers\Admin\GeneratedDocumentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SignatureController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\TemplateFieldController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('permission:view dashboard')
        ->name('dashboard');

    Route::middleware('permission:manage banks')->group(function () {
        Route::resource('banks', BankController::class);
        Route::patch('banks/{bank}/toggle-status', [BankController::class, 'toggleStatus'])->name('banks.toggle-status');
    });

    Route::middleware('permission:manage bank branches')->group(function () {
        Route::resource('bank-branches', BankBranchController::class)->parameters(['bank-branches' => 'bankBranch']);
        Route::patch('bank-branches/{bankBranch}/toggle-status', [BankBranchController::class, 'toggleStatus'])->name('bank-branches.toggle-status');
    });

    Route::middleware('permission:manage templates')->group(function () {
        Route::resource('templates', TemplateController::class);
        Route::patch('templates/{template}/toggle-status', [TemplateController::class, 'toggleStatus'])->name('templates.toggle-status');
    });

    Route::middleware('permission:manage template fields')->group(function () {
        Route::get('templates/{template}/fields/sync', [TemplateFieldController::class, 'sync'])->name('templates.fields.sync');
        Route::post('templates/{template}/fields/sync', [TemplateFieldController::class, 'bulkSync'])->name('templates.fields.bulk-sync');
        Route::post('templates/{template}/fields/bulk-toggle', [TemplateFieldController::class, 'bulkToggle'])->name('templates.fields.bulk-toggle');
        Route::resource('templates.fields', TemplateFieldController::class)->shallow();
        Route::patch('fields/{field}/toggle-status', [TemplateFieldController::class, 'toggleStatus'])->name('fields.toggle-status');
    });

    Route::middleware('permission:manage documents')->group(function () {
        Route::resource('documents', GeneratedDocumentController::class);
    });

    Route::middleware('permission:manage signatures')->group(function () {
        Route::resource('signatures', SignatureController::class);
    });

    Route::middleware('permission:manage users')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    Route::middleware('permission:manage roles')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    Route::middleware('permission:manage permissions')->group(function () {
        Route::resource('permissions', PermissionController::class)->except('show');
    });

    Route::middleware('permission:view download logs')->group(function () {
        Route::get('download-logs', [DownloadLogController::class, 'index'])->name('download-logs.index');
    });
});
