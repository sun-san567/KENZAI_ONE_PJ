<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController; // ← EmployeeController の代わりに UserController を使用
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// 🏠 トップページ
Route::get('/', function () {
    return view('welcome');
});

// 🏠 ダッシュボード（認証必須）
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🛠 Breezeの認証ルート（ログイン・ログアウト・登録）
require __DIR__ . '/auth.php';

// 🔒 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // 📌 ユーザープロフィール
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 📌 顧客管理
    Route::resource('clients', ClientController::class);
    Route::post('clients/import', [ClientController::class, 'import'])->name('clients.import');
    Route::get('clients/download-format', [ClientController::class, 'downloadFormat'])->name('clients.downloadFormat');

    // 📌 担当者管理（users に統合）
    Route::resource('users', UserController::class);
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('users/download-format', [UserController::class, 'downloadFormat'])->name('users.download_format');

    // 📌 会社・部門管理
    Route::resource('companies', CompanyController::class);
    Route::resource('departments', DepartmentController::class);

    // 📌 プロジェクト・フェーズ管理
    Route::resource('projects', ProjectController::class);
    Route::resource('phases', PhaseController::class);

    // 📌 カテゴリ管理
    Route::resource('categories', CategoryController::class);
});
