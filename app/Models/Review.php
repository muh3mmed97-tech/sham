<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // قمنا بإضافة 'user_id' هنا
    protected $fillable = [
        'user_id', 
        'product_id', 
        'order_id', 
        'rating', 
        'comment'
    ];

    // علاقة التقييم بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة التقييم بالمنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}