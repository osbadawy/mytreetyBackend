<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperfaq
 */
class faq extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sub_title', 'type'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = !$lang ? App::getLocale() : $lang;
        $faq_translations = $this->faq_translations->where('lang', $lang)->first();
        return $faq_translations != null ? $faq_translations->$field : $this->$field;
    }

    public function faq_translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }
}
