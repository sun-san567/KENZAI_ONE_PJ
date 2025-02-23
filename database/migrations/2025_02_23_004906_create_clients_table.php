<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // 主キー（取引先ID）
            $table->string('name')->unique()->comment('取引先名'); // 取引先名（ユニーク制約）
            $table->string('address')->nullable()->comment('住所'); // 住所（任意）
            $table->string('phone')->nullable()->comment('電話番号'); // 電話番号（任意）
            $table->string('email')->nullable()->comment('メールアドレス'); // メールアドレス（任意）
            $table->timestamps(); // 作成日時・更新日時
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
