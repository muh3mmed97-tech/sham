<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Store; 
use App\Models\Wallet;
use App\Models\Wishlist;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',               // مضاف: لحفظ رقم هاتف العميل والتاجر
        'address',             // مضاف: لتحديث عنوان التوصيل
        'password',
        'role',
        'otp_code',            // مضاف: لرمز التحقق المؤقت
        'phone_verified_at',   // مضاف: لتأكيد تفعيل الحساب
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
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

    // علاقة المستخدم بقائمة المفضلة
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // علاقة المستخدم بطلباته
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}