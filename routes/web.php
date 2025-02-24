<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// ダッシュボード（認証必須）
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Breezeの認証ルート
require __DIR__ . '/auth.php';

// 認証が必要なルートグループ
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 追加: 顧客管理関連
    Route::resource('clients', ClientController::class);

    // 追加: 担当者管理関連
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('/employees/download-format', [EmployeeController::class, 'downloadFormat'])->name('employees.download_format');

    // 追加: 会社管理関連
    Route::resource('companies', CompanyController::class);

    // 追加: 部門管理関連
    Route::resource('departments', DepartmentController::class);

    // 追加: プロジェクト管理関連
    Route::resource('projects', ProjectController::class);

    // 追加: フェーズ管理関連
    Route::resource('phases', PhaseController::class);
});
