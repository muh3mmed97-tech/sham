<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. جدول التصنيفات
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. جدول المتاجر
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // 3. جدول المنتجات
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('store_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->text('description')->nullable(); // <-- أضفنا هذا السطر للوصف
    $table->decimal('price', 10, 2);
    $table->integer('stock');
    $table->string('image')->nullable();      // <-- أضفنا هذا السطر للصورة
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('categories');
    }
};