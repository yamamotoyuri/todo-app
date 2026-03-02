<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

/**
 * データベース操作（Eloquent）を直接担当するクラス
 */
class TaskRepository
{
    private Task $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /**
     * 全件取得
     */
    public function index(): Collection
    {
        return $this->model->all();
    }

    /**
     * 新規保存
     */
    public function store(array $data): Task
    {
        return $this->model->create($data);
    }

    /**
     * 更新
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    /**
     * 削除
     */
    public function destroy(Task $task): bool
    {
        return $task->delete();
    }
}