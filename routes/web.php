<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;

use App\Models\Phase;
use App\Models\Project;


Route::get('/', function () {
    $phases = Phase::orderBy('order')->get(); // フェーズの取得
    $projects = Project::all(); // プロジェクトの取得
    return view('dashboard', compact('phases', 'projects'));
})->name('dashboard');


Route::resource('phases', PhaseController::class);
Route::resource('projects', ProjectController::class);
Route::resource('companies', CompanyController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('employees', EmployeeController::class);
