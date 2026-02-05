<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\Task;
use Illuminate\Support\Facades\File;

class TodoExportController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->query('type', 'excel');

        $date = Carbon::now()->format('Ymd');
        $baseName  = "todo_{$date}";
        $excelName = "{$baseName}.xlsx";
        $pdfName   = "{$baseName}.pdf";

        $tmpDir = storage_path('app/tmp');
        File::ensureDirectoryExists($tmpDir);

        $todos = Task::all();

        // ===== Excel作成 =====
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Task List');

        // 1. ヘッダーのデザイン
        $headers = ['ID', 'タイトル', '完了ステータス'];
        $sheet->fromArray($headers, null, 'A1');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'], 
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

        // 2. データの流し込み
        $row = 2;

        // --- 2. データの流し込み 部分 ---
        foreach ($todos as $todo) {
            $sheet->setCellValue('A' . $row, $todo->id);
            $sheet->setCellValue('B' . $row, $todo->title);
            
            $isDone = $todo->is_completed; 

            $sheet->setCellValue('C' . $row, $isDone ? '完了' : '未完了');
            
            if ($isDone) {
                $sheet->getStyle("A{$row}:C{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
            $row++;
        }

        // 3. 全体に罫線を引く & 列幅の自動調整
        $lastRow = $row - 1;
        $range = "A1:C{$lastRow}";

        // 日本語フォントを明示的に指定（PDFの文字化け対策）
        $sheet->getStyle($range)->getFont()->setName('IPAexGothic');
        
        // 罫線と垂直方向の中央揃え
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $excelPath = "{$tmpDir}/{$excelName}";
        (new Xlsx($spreadsheet))->save($excelPath);

        // ===== PDF変換 =====
        if ($type === 'pdf') {
            $command = sprintf(
                'libreoffice --headless --convert-to pdf --outdir %s %s',
                escapeshellarg($tmpDir),
                escapeshellarg($excelPath)
            );

            exec($command, $output, $resultCode);
            $generatedPdfPath = "{$tmpDir}/{$baseName}.pdf";

            if ($resultCode !== 0 || !file_exists($generatedPdfPath)) {
                abort(500, 'PDFの生成に失敗しました');
            }

            $filePath = $generatedPdfPath;
            $downloadName = $pdfName;
        } else {
            $filePath = $excelPath;
            $downloadName = $excelName;
        }

        return response()->download($filePath, $downloadName)->deleteFileAfterSend(true);
    }
}