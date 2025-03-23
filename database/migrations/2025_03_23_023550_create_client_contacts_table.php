<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('name');                  // 担当者名（例：田中太郎）
            $table->string('position')->nullable();  // 基本役職（例：工事部長）
            $table->string('phone')->nullable();     // 直通・携帯番号
            $table->string('email')->nullable();     // メールアドレス
            $table->text('note')->nullable();        // 備考欄（自由記述）
            $table->timestamps();                    // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_contacts');
    }
};
