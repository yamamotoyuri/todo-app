<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

/**
 * タスクの基本操作（CRUD）を制御するコントローラー
 */
class TaskController extends Controller
{
    private TaskService $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * タスク一覧を取得
     * * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->taskService->index();
    }

    /**
     * 新しいタスクを作成
     * * @param StoreTaskRequest $request バリデーション済みリクエスト
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        return $this->taskService->store($request->validated());
    }

    /**
     * タスクを更新
     * * @param UpdateTaskRequest $request バリデーション済みリクエスト
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        return $this->taskService->update($request->validated(), $task);
    }

    /**
     * タスクを削除
     * * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        return $this->taskService->destroy($task);
    }
}