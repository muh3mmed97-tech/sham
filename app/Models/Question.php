<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = ['product_id', 'user_id', 'content', 'answer'];

    // العلاقة مع المستخدم
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المنتج (مهمة جداً للتحقق من ملكية التاجر للرد)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}