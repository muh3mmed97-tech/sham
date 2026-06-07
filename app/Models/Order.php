<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'customer_id',
        'product_id', // أضفنا هذا الحقل
        'product_name',
        'quantity',
        'total_price',
        'status',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // إضافة العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}