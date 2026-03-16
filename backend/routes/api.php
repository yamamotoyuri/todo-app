<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoExportController; 
use Illuminate\Support\Facades\Route;

// --- Todo CRUD ---
Route::get('todos', [TodoController::class, 'index']);      // 一覧取得
Route::post('todos', [TodoController::class, 'store']);     // 新規作成
Route::put('todos/{todo}', [TodoController::class, 'update']); // 更新(完了/未完了)
Route::delete('todos/{todo}', [TodoController::class, 'destroy']); // 削除

// --- Export ---
Route::get('/todo/export', [TodoExportController::class, 'export']); // Excel/PDF出力