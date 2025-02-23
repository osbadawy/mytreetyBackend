<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAppTranslation
 */
class AppTranslation extends Model
{
    use HasFactory;

    protected $guarded = [];
}
