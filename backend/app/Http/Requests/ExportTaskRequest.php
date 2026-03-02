<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * エクスポート時のバリデーションを担当
 */
class ExportTaskRequest extends FormRequest
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
            // excelかpdfのいずれかであること
            'type' => 'sometimes|string|in:excel,pdf',
        ];
    }
}