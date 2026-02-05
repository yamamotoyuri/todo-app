<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoExportController; 
use Illuminate\Support\Facades\Route;

// --- Task CRUD ---
Route::get('tasks', [TaskController::class, 'index']);      // 一覧取得
Route::post('tasks', [TaskController::class, 'store']);     // 新規作成
Route::put('tasks/{task}', [TaskController::class, 'update']); // 更新(完了/未完了)
Route::delete('tasks/{task}', [TaskController::class, 'destroy']); // 削除

// --- Export ---
Route::get('/todo/export', [TodoExportController::class, 'export']); // Excel/PDF出力