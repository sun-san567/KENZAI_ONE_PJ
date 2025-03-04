<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // 🔹 プロジェクトに紐付く
            $table->string('file_name'); // 🔹 元のファイル名
            $table->string('file_path')->unique(); // 🔹 ストレージのパス（重複防止）
            $table->string('mime_type')->nullable(); // 🔹 MIMEタイプ（NULL可）
            $table->string('file_extension', 20); // 🔹 拡張子 (例: dwg, dxf, step, iges)
            $table->bigInteger('size'); // 🔹 ファイルサイズ（バイト、大容量対応）
            $table->string('category')->nullable(); // 🔹 ファイルの種類（例: CAD, 図面, 見積書, 契約書）
            $table->string('preview_path')->nullable(); // 🔹 プレビュー用画像のパス
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('cascade'); // 🔹 ユーザー情報（NULL許可）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
