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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // ゴールとの紐付け（goals.idを参照）
            $table->foreignId('goal_id')->constrained()->onDelete('cascade');
            $table->string('task'); // タスク名
            $table->integer('flg')->default(0); // 完了フラグ
            $table->date('target_date')->nullable(); // カレンダーで使用する期日
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};