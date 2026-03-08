<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

/**
 * データベース操作（Eloquent）を直接担当するクラス
 */
class TodoRepository
{
    private Task $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /**
     * 全件取得
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->model->all();
    }

    /**
     * 新規保存
     * @return Task
     */
    public function store(array $data): Task
    {
        return $this->model->create($data);
    }

    /**
     * 更新
     * @return Task 
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    /**
     * 削除
     * @return bool
     */
    public function destroy(Task $task): bool
    {
        return $task->delete();
    }

    /**
    * タスクを全件取得して配列で返す（エクスポート用）
    * @return Collection
    */
    public function getAllForExport(): Collection
    {
        return $this->model->all();
    }
}