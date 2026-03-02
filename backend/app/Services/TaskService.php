<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskRepository->index();
        return response()->json($tasks);
    }

    public function store(array $validatedData): JsonResponse
    {
        $data = [
            'title' => $validatedData['title'],
            'is_completed' => false,
        ];

        $task = $this->taskRepository->store($data);
        return response()->json($task, 201); 
    }

    public function update(array $validatedData, Task $task): JsonResponse
    {
        $updatedTask = $this->taskRepository->update($task, $validatedData);
        return response()->json($updatedTask);
    }
  
    public function destroy(Task $task): JsonResponse
    {
        $this->taskRepository->destroy($task);
        return response()->json(['message' => '削除しました']);
    }
}