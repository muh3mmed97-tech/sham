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
        Schema::table('stores', function (Blueprint $table) {
            // إضافة عمود الشعار فقط إذا لم يكن موجوداً مسبقاً
            if (!Schema::hasColumn('stores', 'logo')) {
                $table->string('logo')->nullable()->after('name');
            }

            // إضافة عمود الوصف فقط إذا لم يكن موجوداً مسبقاً
            if (!Schema::hasColumn('stores', 'description')) {
                $table->text('description')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'logo')) {
                $table->dropColumn('logo');
            }
            if (Schema::hasColumn('stores', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};