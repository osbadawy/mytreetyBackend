<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperguide
 */
class guide extends Model
{
    use HasFactory;

    public function getTranslation($field = '', $lang = false){
        $lang = !$lang ? App::getLocale() : $lang;
        $guide_translations = $this->guide_translations->where('lang', $lang)->first();
        return $guide_translations != null ? $guide_translations->$field : $this->$field;
    }

    public function guide_translations(): HasMany
    {
    	return $this->hasMany(GuideTranslation::class);
    }
}
