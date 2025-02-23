<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSustainabilityRequest
 */
class SustainabilityRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'files', 'sustainability_id', 'product_id', 'sustainability_id'];

    public function sustainability(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sustainability::class, 'sustainability_id');
    }
}
