<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'إلكترونيات', 
            'أزياء', 
            'مستحضرات التجميل', 
            'المنزل', 
            'أجهزة كهربائية', 
            'سوبر ماركت', 
            'أطفال', 
            'الرجال', 
            'النساء'
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}