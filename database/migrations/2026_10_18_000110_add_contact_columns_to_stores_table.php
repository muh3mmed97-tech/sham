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
            if (!Schema::hasColumn('stores', 'phone')) {
                $table->string('phone', 20)->nullable()->after('name');
            }
            if (!Schema::hasColumn('stores', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('stores', 'logo')) {
                $table->string('logo')->nullable()->after('address');
            }
            if (!Schema::hasColumn('stores', 'description')) {
                $table->text('description')->nullable()->after('logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['phone', 'address', 'logo', 'description'] as $column) {
                if (Schema::hasColumn('stores', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};