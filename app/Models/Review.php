<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperReview
 */
class Review extends Model
{
    protected $fillable = ['product_id', 'name', 'email', 'rating', 'comment', 'status'];


    protected $hidden = [
        'product'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
