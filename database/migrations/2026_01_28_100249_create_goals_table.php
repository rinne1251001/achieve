<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            // ユーザーとの紐付け（users.idを参照）
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('goal'); // 目標名
            $table->integer('flg')->default(0); // 完了フラグ
            $table->date('target_date')->nullable(); // 目標の期日（必要であれば）
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
