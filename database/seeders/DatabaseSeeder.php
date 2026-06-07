<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. استدعاء Seeder الأقسام لإنشاء الأقسام التسعة
        $this->call([
            CategorySeeder::class,
        ]);

        // 2. إنشاء التاجر التجريبي إذا لم يكن موجوداً
        User::firstOrCreate(
            ['email' => 'vendor@sham.com'],
            [
                'name' => 'تاجر تجريبي',
                'password' => Hash::make('password'),
                'role' => 'vendor'
            ]
        );

        // 3. إنشاء العميل التجريبي إذا لم يكن موجوداً
        User::firstOrCreate(
            ['email' => 'customer@sham.com'],
            [
                'name' => 'عميل تجريبي',
                'password' => Hash::make('password'),
                'role' => 'customer'
            ]
        );
    }
}