<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
// استيراد الموديلات اللازمة
use App\Models\Store; 
use App\Models\Wallet;
use App\Models\Wishlist;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // منطق إنشاء المحفظة تلقائياً عند إنشاء المستخدم
    protected static function booted()
    {
        static::created(function ($user) {
            $user->wallet()->create([
                'balance' => 0.00,
                'currency' => 'USD',
            ]);
        });
    }

    // علاقة المستخدم بمتجره
    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    // علاقة المستخدم بمحفظته
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    // علاقة المستخدم بقائمة المفضلة (التي قمنا بإضافتها)
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}