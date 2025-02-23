<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsedReferralCode extends Model
{
    use HasFactory;

    public function customer(): BelongsTo
    {
    	return $this->belongsTo(User::class,'user_id');
    }

    public function referral_owner(): BelongsTo
    {
    	return $this->belongsTo(User::class,'referral_code','referral_code');
    }

    public function orders(): HasMany
    {
    	return $this->hasMany(Order::class,'referral_code','referral_code');
    }
}
