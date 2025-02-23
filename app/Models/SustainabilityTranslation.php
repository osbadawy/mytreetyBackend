<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSustainabilityTranslation
 */
class SustainabilityTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'lang', 'sustainability_id'];

    public function sustainability(): BelongsTo
    {
        return $this->belongsTo(Sustainability::class);
    }
}
