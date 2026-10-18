<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Product;
use App\Models\StoreReview;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'logo',        // مضاف: لحفظ مسار شعار المتجر
        'phone',       // مضاف: لحفظ رقم هاتف التواصل
        'address',     // مضاف: لحفظ موقع/عنوان المتجر
        'description', // مضاف: لحفظ نبذة المتجر
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // علاقة تقييمات المتجر
    public function reviews(): HasMany
    {
        return $this->hasMany(StoreReview::class, 'store_id', 'user_id');
    }
}