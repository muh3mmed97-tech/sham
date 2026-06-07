<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    // أضفنا user_id و product_id للسماح بالإضافة الجماعية
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // علاقة المفضلة بالمنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // علاقة المفضلة بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}