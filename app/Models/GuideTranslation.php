<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperGuideTranslation
 */
class GuideTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['title','sub_title'];

    public function guide(){
    	return $this->belongsTo(guide::class);
    }
}
