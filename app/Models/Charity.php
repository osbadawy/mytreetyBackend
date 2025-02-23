<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCharity
 */
class Charity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'address', 'city', 'postal_code', 'phone', 'country',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
