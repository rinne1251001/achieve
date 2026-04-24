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
            $table->tinyInteger('flg')->default(0)->comment('0:未完了, 1:完了'); // 完了フラグ
            $table->date('target_date')->nullable(); // カレンダーで使用する期日
            $table->timestamps();
            $table->text('detail')->nullable(); //タスクの説明
            $table->index(['goal_id', 'flg']);   // ← 追加
            $table->index('target_date');
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