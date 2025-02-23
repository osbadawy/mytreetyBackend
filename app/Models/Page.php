<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPage
 */
class Page extends Model
{
  public function getTranslation($field = '', $lang = false){
      $lang = !$lang ? App::getLocale() : $lang;
      $page_translation = $this->hasMany(PageTranslation::class)->where('lang', $lang)->first();
      return $page_translation != null ? $page_translation->$field : $this->$field;
  }

  public function page_translations(): HasMany
  {
    return $this->hasMany(PageTranslation::class);
  }
}
