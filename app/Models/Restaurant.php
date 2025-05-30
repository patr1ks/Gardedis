<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Restaurant extends Model
{
    use HasSlug, HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'published',
        'price',
        'owner',
        'category_id',
        'layout_json',
    ];

    protected $casts = [
        'layout_json' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function restaurant_images()
    {
        return $this->hasMany(Image::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
