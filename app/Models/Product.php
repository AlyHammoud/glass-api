<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'titleArabic',
        'briefDetails',
        'briefDetailsArabic',
        'user_id',
        'service_id',
        'cover',
        'fullDetails',
        'fullDetailsArabic',
        'slug'
    ];

    /**
     * Get the options for generating the slug.
     */

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }


    public function productImages()
    {
        return $this->hasMany(ProductsImage::class);
    }

    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
