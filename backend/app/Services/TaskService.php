<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskService
{
    protected $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function index(): JsonResponse
    {
        $tasks = $this->todoRepository->index();
        return response()->json($tasks);
    }

    public function store(array $validatedData): JsonResponse
    {
        $data = [
            'title' => $validatedData['title'],
            'is_completed' => false,
        ];

        $task = $this->todoRepository->store($data);
        return response()->json($task, 201); 
    }

    public function update(array $validatedData, Task $task): JsonResponse
    {
        $updatedTask = $this->todoRepository->update($task, $validatedData);
        return response()->json($updatedTask);
    }
  
    public function destroy(Task $task): JsonResponse
    {
        $this->todoRepository->destroy($task);
        return response()->json(['message' => '削除しました']);
    }
}