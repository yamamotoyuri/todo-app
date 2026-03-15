<?php

namespace App\Http\Controllers;
use App\Http\Requests\ExportTodoRequest;
use App\Services\TodoExportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * ToDoリストのエクスポート（ダウンロード）を制御するコントローラー
 */
class TodoExportController extends Controller
{
    private TodoExportService $todoExportService;

    /**
     * @param TodoExportService $todoExportService
     */
    public function __construct(TodoExportService $todoExportService)
    {
        $this->todoExportService = $todoExportService;
    }

    /**
     * エクスポート処理を実行し、ファイルをダウンロードさせる
     * @param StoreExportRequest $request バリデーション済みリクエスト
     * @return BinaryFileResponse
     */
    public function export(ExportTodoRequest $request): BinaryFileResponse
    {
        // 1. バリデーション済みの'type'（excel or pdf）のみを抽出
        $type = $request->validated('type');
        // 2. Serviceに処理を依頼
        $fileInfo = $this->todoExportService->export($type);

        // 3. 生成されたファイルをダウンロードレスポンスとして返す
        return response()
            ->download($fileInfo['path'], $fileInfo['name'])
            ->deleteFileAfterSend(true); // 送信後に一時ファイルを自動削除
    }
}