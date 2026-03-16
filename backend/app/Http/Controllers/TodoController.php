<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Services\TodoService;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;

/**
 * タスクの基本操作（CRUD）を制御するコントローラー
 */
class TodoController extends Controller
{
    private TodoService $todoService;

    /**
     * @param TodoService $todoService
     */
    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    /**
     * タスク一覧を取得
     *  @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->todoService->index();
    }

    /**
     * 新しいタスクを作成
     *  @param StoreTodoRequest $request バリデーション済みリクエスト
     * @return JsonResponse
     */
    public function store(StoreTodoRequest $request): JsonResponse
    {
        return $this->todoService->store($request->validated());
    }

    /**
     * タスクを更新
     *　@param UpdateTodoRequest $request バリデーション済みリクエスト
     * @param Todo $todo 更新対象のタスク
     * @return JsonResponse
     */
    public function update(UpdateTodoRequest $request, Todo $todo): JsonResponse
    {
        return $this->todoService->update($request->validated(), $todo);
    }

    /**
     * タスクを削除
     * @param Todo $todo 削除対象のタスク
     * @return JsonResponse
     */
    public function destroy(Todo $todo): JsonResponse
    {
        return $this->todoService->destroy($todo);
    }
}