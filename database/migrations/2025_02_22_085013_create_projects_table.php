<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // ✅ `projects` テーブル作成
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phase_id')->constrained('phases')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('revenue', 10, 2)->default(0);
            $table->decimal('profit', 10, 2)->default(0);
            $table->date('start_date')->nullable(); // 着工日
            $table->date('end_date')->nullable();   // 竣工日
            $table->date('estimate_deadline')->nullable(); // 見積期限
            $table->timestamps();
        });

        // ✅ 多対多の `project_categories` テーブル作成
        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        // ✅ 削除順を修正（外部キー制約の関係で `project_categories` を先に削除）
        Schema::dropIfExists('project_categories');
        Schema::dropIfExists('projects');
    }
};
