<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperFaqTranslation
 */
class FaqTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sub_title'];

    public function faq(): BelongsTo
    {
        return $this->belongsTo(faq::class);
    }

}
