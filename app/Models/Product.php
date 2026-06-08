<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // هنا نضع فقط الأعمدة الموجودة فعلياً في جدول products
    protected $fillable = [
        'store_id', 
        'category_id', 
        'name', 
        'description', 
        'price', 
        'stock', // تم اعتماد stock كاسم نهائي ومعتمد
        'image'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}