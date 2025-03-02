<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('project_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // プロジェクトに紐づく
            $table->decimal('revenue', 10, 2)->default(0); // 売上
            $table->decimal('profit', 10, 2)->default(0);  // 粗利
            $table->decimal('cost', 10, 2)->default(0);    // 原価（追加）
            $table->timestamp('changed_at')->useCurrent(); // 変更日時
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_histories');
    }
};
