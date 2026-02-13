<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('learning_logs', function (Blueprint $table) {
            $table->softDeletes(); // deleted_at を追加
        });
    }

    public function down(): void
    {
        Schema::table('learning_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
