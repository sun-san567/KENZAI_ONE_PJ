<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ClientContactController;
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

    // 📌 会社・部門管理（統合）
    Route::get('/company', [CompanyController::class, 'index'])->name('company.index'); // 会社情報＋部門情報
    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create'); // 会社作成
    Route::get('/company/{company}/edit', [CompanyController::class, 'edit'])->name('company.edit'); // 会社編集
    Route::put('/company/{company}', [CompanyController::class, 'update'])->name('company.update'); // 会社情報更新


    // 部門管理
    Route::resource('departments', DepartmentController::class); // 部門の一覧・作成・編集・削除
    // 部門追加・削除は `resource` で管理できるため不要

    // 📌 プロジェクト・フェーズ管理
    Route::resource('projects', ProjectController::class);
    Route::resource('phases', PhaseController::class);

    // 📌 カテゴリ管理
    Route::resource('categories', CategoryController::class);

    // 📌 担当者管理
    Route::resource('clients.contacts', ClientContactController::class)
        ->except(['show'])  // showアクションは不要なので除外
        ->middleware(['auth', 'verified']);  // 認証済みユーザーのみアクセス可能



    // プロジェクトファイル関連のルート
    Route::prefix('projects/{project}/files')->group(function () {
        Route::get('/', [ProjectFileController::class, 'index'])->name('projects.files.index');
        Route::post('/upload', [ProjectFileController::class, 'upload'])->name('projects.files.upload');
        Route::get('/{file}/download', [ProjectFileController::class, 'download'])->name('projects.files.download');
    });

    Route::delete('/projects/{project}/files/{file}', [ProjectFileController::class, 'destroy'])
        ->name('projects.files.destroy');

    Route::post('/projects/{project}/files/bulk-delete', [ProjectFileController::class, 'bulkDelete'])
        ->name('projects.files.bulk-delete');

    // ファイルのプレビュー関連ルート
    Route::get('/projects/{project}/files/{file}/preview', [ProjectFileController::class, 'preview'])
        ->name('projects.files.preview');

    Route::get('/projects/{project}/files/{file}/content', [ProjectFileController::class, 'previewContent'])
        ->name('projects.files.preview-content');

    // プロジェクト一覧表示
    Route::get('/projects', [App\Http\Controllers\ProjectController::class, 'index'])
        ->name('projects.index');

    // プロジェクト保存
    Route::post('/projects', [App\Http\Controllers\ProjectController::class, 'store'])
        ->name('projects.store');

    // プロジェクト更新
    Route::put('/projects/{project}', [App\Http\Controllers\ProjectController::class, 'update'])
        ->name('projects.update');

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // ファイルお気に入り
    Route::post('/project-files/{projectFile}/favorite', [ProjectFileController::class, 'favorite'])->name('project-files.favorite');
    Route::delete('/project-files/{projectFile}/favorite', [ProjectFileController::class, 'unfavorite'])->name('project-files.unfavorite');
});

// 一時的な検証用ルート（確認後削除可能）
Route::get('/debug-projects', function () {
    $projects = \App\Models\Project::all();
    $data = [];

    foreach ($projects as $project) {
        $data[] = [
            'id' => $project->id,
            'name' => $project->name,
            'raw_estimate_deadline' => $project->getRawOriginal('estimate_deadline'),
            'casted_estimate_deadline' => $project->estimate_deadline,
            'formatted' => $project->estimate_deadline ? \Carbon\Carbon::parse($project->estimate_deadline)->format('Y/m/d') : null
        ];
    }

    return $data;
});
