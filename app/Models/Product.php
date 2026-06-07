<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'store_id', 
        'category_id', 
        'name', 
        'description', 
        'price', 
        'stock', 
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

    // علاقة التقييمات
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // علاقة الأسئلة: المنتج الواحد له عدة أسئلة
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}