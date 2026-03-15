<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

/**
 * データベース操作（Eloquent）を直接担当するクラス
 */
class TodoRepository
{
    private Todo $model;

    public function __construct(Todo $model)
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
     * @return Todo
     */
    public function store(array $data): Todo
    {
        return $this->model->create($data);
    }

    /**
     * 更新
     * @return Todo
     */
    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);
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
        return $this->model->all();
    }
}