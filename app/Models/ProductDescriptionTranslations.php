<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDescriptionTranslations extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sub_title','product_description_id','lang'];


    public function product_description(): BelongsTo
    {
        return $this->belongsTo(ProductDescription::class, 'product_description_id');
    }
}
