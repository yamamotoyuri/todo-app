<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * 権限チェック（今回は常に許可）
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * バリデーションルール
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
        ];
    }
}