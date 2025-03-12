<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class MenuItem extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'image_url',
        'description',
        'is_available',
        'is_published',
        'category_id'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
}
