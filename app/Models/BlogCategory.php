<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperBlogCategory
 */
class BlogCategory extends Model
{
    use SoftDeletes;

    public function posts(): HasMany
    {
        return $this->hasMany(Blog::class);
    }
}
