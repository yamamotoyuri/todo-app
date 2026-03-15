<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;

class TodoService
{
    protected $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function index(): JsonResponse
    {
        $todos = $this->todoRepository->index();
        return response()->json($todos);
    }

    public function store(array $validatedData): JsonResponse
    {
        $data = [
            'title' => $validatedData['title'],
            'is_completed' => false,
        ];

        $todo = $this->todoRepository->store($data);
        return response()->json($todo, 201); 
    }

    public function update(array $validatedData, Todo $todo): JsonResponse
    {
        $updatedTodo = $this->todoRepository->update($todo, $validatedData);
        return response()->json($updatedTodo);
    }
  
    public function destroy(Todo $todo): JsonResponse
    {
        $this->todoRepository->destroy($todo);
        return response()->json(['message' => '削除しました']);
    }
}