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
            $table->string('file_path'); // 🔹 ストレージのパス
            $table->string('file_type'); // 🔹 拡張子
            $table->integer('size'); // 🔹 ファイルサイズ（バイト）
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade'); // 🔹 アップロードユーザー
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
