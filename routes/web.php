<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ProjectController;
use App\Models\Phase;
use App\Models\Project;

Route::get('/', function () {
    $phases = Phase::orderBy('order')->get(); // フェーズの取得
    $projects = Project::all(); // プロジェクトの取得
    return view('dashboard', compact('phases', 'projects'));
})->name('dashboard');


Route::resource('phases', PhaseController::class);
Route::resource('projects', ProjectController::class);
