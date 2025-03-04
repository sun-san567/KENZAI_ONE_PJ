<?php

use App\Http\Controllers\ProjectFileController;
use Illuminate\Support\Facades\Route;

Route::prefix('projects/{project}/files')->group(function () {
    Route::post('/', [ProjectFileController::class, 'upload']); // ファイルアップロード
    Route::get('/', [ProjectFileController::class, 'index']); // ファイル一覧取得
    Route::delete('/{file}', [ProjectFileController::class, 'destroy']); // ファイル削除
});
