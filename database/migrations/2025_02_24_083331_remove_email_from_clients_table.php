<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('clients', 'email')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('clients', 'email')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('email')->nullable(); // もともと `nullable()` なら追加時も合わせる
            });
        }
    }
};
