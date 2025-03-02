<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('project_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // プロジェクトID
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // カテゴリID
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_category');
    }
};
