<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

/**
 * データベース操作（Eloquent）を直接担当するクラス
 */
class TodoRepository
{
    private Todo $todoModel;

    public function __construct(Todo $todoModel)
    {
        $this->todoModel = $todoModel;
    }

    /**
     * 全件取得
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->todoModel->all();
    }

    /**
     * 新規保存
     * @return Todo
     */
    public function store(array $todoData): Todo
    {
        return $this->todoModel->create($todoData);
    }

    /**
     * 更新
     * @return Todo
     */
    public function update(Todo $todo, array $todoData): Todo
    {
        $todo->update($todoData);
        return $todo;
    }

    /**
     * 削除
     * @return bool
     */
    public function destroy(Todo $todo): bool
    {
        return $todo->delete();
    }

    /**
    * タスクを全件取得して配列で返す（エクスポート用）
    * @return Collection
    */
    public function getAllForExport(): Collection
    {
        return $this->todoModel->all();
    }
}