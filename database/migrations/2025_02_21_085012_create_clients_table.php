<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーションの実行
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // 自動インクリメントID
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // 会社ID（削除時にクライアントも削除）
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null'); // 部門ID（任意）
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // 1担当者のみ（任意）
            $table->string('name')->unique(); // 取引先名（ユニーク）
            $table->string('phone')->nullable(); // 電話番号（任意）
            $table->text('address')->nullable(); // 住所（任意）
            $table->timestamps(); // 作成日・更新日
        });
    }

    /**
     * マイグレーションのロールバック
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
