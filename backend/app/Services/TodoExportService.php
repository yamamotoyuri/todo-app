<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Collection;

/**
 * ToDoリストのエクスポート（Excel/PDF）に関するビジネスロジックを担当
 */
class TodoExportService
{
    private TodoExportRepository $repo;

    /**
     * @param TodoExportRepository $repo
     */
    public function __construct(TodoExportRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * エクスポート処理を実行
     * @param string|null $type 'excel' または 'pdf'
     * @return array{path: string, name: string} 生成されたファイルの情報
     */
    public function export(?string $type): array
    {
        $date = Carbon::now()->format('Ymd');
        $baseName = "todo_{$date}";
        $tmpDir = storage_path('app/tmp');
        
        File::ensureDirectoryExists($tmpDir);

        // 1. Repositoryからデータを取得
        $todos = $this->repo->getAllForExport();

        // 2. Excelファイルを作成
        $excelPath = $this->createExcel($todos, $tmpDir, $baseName);

        // 3. PDF変換が必要な場合は変換
        if ($type === 'pdf') {
            return $this->convertToPdf($excelPath, $tmpDir, $baseName);
        }

        return [
            'path' => $excelPath,
            'name' => "{$baseName}.xlsx"
        ];
    }

    /**
     * Excelファイルを生成
     * @param Collection $todos
     * @param string $tmpDir
     * @param string $baseName
     * @return string 作成したExcelのパス
     */
    private function createExcel(Collection $todos, string $tmpDir, string $baseName): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Task List');

        // ヘッダー作成
        $this->setExcelHeader($sheet);

        // データの流し込み
        $currentRow = 2;
        foreach ($todos as $todo) {
            $this->setExcelRowData($sheet, $currentRow, $todo);
            $currentRow++;
        }

        // スタイル調整（全体）
        $this->applyGlobalStyles($sheet, $currentRow - 1);

        $path = "{$tmpDir}/{$baseName}.xlsx";
        (new Xlsx($spreadsheet))->save($path);
        
        return $path;
    }

    /**
     * Excelのヘッダー（1行目）をセット・装飾
     * @param Worksheet $sheet
     * @return bool
     */
    private function setExcelHeader(Worksheet $sheet): bool
    {
        try {
            $sheet->fromArray(['ID', 'タイトル', '完了ステータス'], null, 'A1');
            
            $sheet->getStyle('A1:C1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
        } catch (\Exception $e) {
            \Log::error("Excelヘッダー設定失敗: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 各行にデータをセットし、完了済みの場合は装飾
     * @param Worksheet $sheet
     * @param int $row
     * @param \App\Models\Task $todo
     * @return void
     */
    private function setExcelRowData(Worksheet $sheet, int $row, \App\Models\Task $todo): void
    {
        $sheet->setCellValue('A' . $row, $todo->id);
        $sheet->setCellValue('B' . $row, $todo->title);
        $sheet->setCellValue('C' . $row, $todo->is_completed ? '完了' : '未完了');
        
        if ($todo->is_completed) {
            $sheet->getStyle("A{$row}:C{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F2F2F2');
        }
    }

    /**
     * 全体のスタイル設定（フォント・罫線・幅自動調整）
     * @param Worksheet $sheet
     * @param int $lastRow
     * @return void
     */
    private function applyGlobalStyles(Worksheet $sheet, int $lastRow): void
    {
        $range = "A1:C{$lastRow}";
        
        $sheet->getStyle($range)->getFont()->setName('IPAexGothic');
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * ExcelをPDFに変換
     * @param string $excelPath
     * @param string $tmpDir
     * @param string $baseName
     * @return array{path: string, name: string}
     */
    private function convertToPdf(string $excelPath, string $tmpDir, string $baseName): array
    {
        $command = sprintf(
            'libreoffice --headless --convert-to pdf --outdir %s %s',
            escapeshellarg($tmpDir),
            escapeshellarg($excelPath)
        );

        exec($command, $output, $resultCode);
        $pdfPath = "{$tmpDir}/{$baseName}.pdf";

        if ($resultCode !== 0 || !file_exists($pdfPath)) {
            abort(500, 'PDFの生成に失敗しました');
        }

        return [
            'path' => $pdfPath,
            'name' => "{$baseName}.pdf"
        ];
    }
}