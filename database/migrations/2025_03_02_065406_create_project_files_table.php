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
            $table->string('file_path'); // 🔹 ストレージのパス（重複防止のため `unique` を削除）
            $table->string('mime_type')->nullable(); // 🔹 MIMEタイプ（例: application/pdf, image/png）
            $table->string('file_extension', 255)->nullable(); // 🔹 拡張子（長めに設定）
            $table->unsignedBigInteger('size'); // 🔹 ファイルサイズ（大容量対応）
            $table->string('category')->nullable(); // 🔹 ファイルの種類（後から設定可能）
            $table->string('preview_path')->nullable(); // 🔹 プレビュー画像のパス（画像ファイルのみ）
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('cascade'); // 🔹 アップロードユーザー情報（NULL許可）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
