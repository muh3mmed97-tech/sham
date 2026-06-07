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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // العميل
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // المنتج
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // للتأكد من أنه اشترى المنتج فعلاً
            $table->integer('rating'); // التقييم من 1 إلى 5
            $table->text('comment')->nullable(); // التعليق (اختياري)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};