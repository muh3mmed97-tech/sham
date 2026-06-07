<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // السماح بتعبئة هذه الحقول عند إنشاء سجل جديد
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // علاقة السلة بجدول المنتجات (كل سلة مرتبطة بمنتج)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // علاقة السلة بالمستخدم (اختياري إذا كنت ستحتاجه لاحقاً)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}