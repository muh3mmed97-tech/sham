<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    // السماح بالتعديل الجماعي لهذه الحقول
    protected $fillable = ['user_id', 'balance', 'currency'];

    // علاقة المحفظة بالمستخدم (عكس العلاقة في User)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}