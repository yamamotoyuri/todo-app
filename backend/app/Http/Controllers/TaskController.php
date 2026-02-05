<?php
namespace App\Http\Controllers;

use App\Models\Task; 
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * ToDo一覧を取得する
     */
    public function index()
    {
        return Task::orderBy('created_at', 'desc')->get();
    }

    /**
     * 新しいToDoを保存する
     */
    public function store(Request $request)

        // バリデーション（タイトルが空じゃないかチェック）
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'is_completed' => false,
        ]);

        return response()->json($task, 201);
    }

    /**
     * ToDoの状態（完了/未完了）を更新する
     */
    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return response()->json($task);
    }

    /**
     * ToDoを削除する
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => '削除しました']);
    }
}