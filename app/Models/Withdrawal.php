<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    // السماح لـ Laravel بتعبئة هذه الحقول عند إنشاء طلب سحب جديد
    protected $fillable = ['user_id', 'amount', 'method', 'status'];

    // العلاقة مع المستخدم (اختياري لكن مفيد)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}