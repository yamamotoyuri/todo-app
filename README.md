# Todo Management System (Full-stack)

## 概要
LaravelとReactを使用した、Excel/PDF出力機能付きのTodo管理システムです。

## 主な機能
- [x] **Todo CRUD**: タスクの作成、一覧、更新、削除
- [x] **高精度エクスポート**: Excelへの書き出し（完了済みタスクのグレーアウト対応）
- [x] **PDF変換**: LibreOfficeを介したサーバーサイドPDF生成
- [x] **自動クリーンアップ**: ファイル送信後のサーバー内一時ファイル自動削除

## 使用技術
- **Backend**: Laravel 11 / PHP 8.2
- **Frontend**: React / Vite
- **Infrastructure**: Docker / LibreOffice (for PDF conversion)
- **Database**: PostgreSQL (or MySQL)
