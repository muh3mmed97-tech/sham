<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // كود الخصم (مثلا: FURAT2026)
            $table->enum('type', ['fixed', 'percent']); // نوع الخصم: مبلغ ثابت أو نسبة مئوية
            $table->decimal('value', 10, 2); // قيمة الخصم (مثلا 10 أو 15%)
            $table->decimal('min_spend', 10, 2)->default(0); // الحد الأدنى للطلب لتفعيل الكوبون
            $table->integer('usage_limit')->nullable(); // عدد مرات الاستخدام المسموحة
            $table->integer('used_count')->default(0); // عدد مرات الاستخدام الفعلية
            $table->timestamp('expires_at')->nullable(); // تاريخ انتهاء الصلاحية
            $table->timestamps();
        });

        // إضافة حقل الكوبون لجدول السلة أو الطلبات إن لم يكن موجوداً
        Schema::table('orders', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('total_price');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'discount_amount']);
        });
        Schema::dropIfExists('coupons');
    }
};