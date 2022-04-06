<?php

namespace Detosphere\BlogPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Detosphere\BlogPackage\Traits\HasUuid;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasSlug, HasUuid;

    protected $guarded = ['id', 'uuid'];

    protected static function newFactory()
    {
        return \Detosphere\BlogPackage\Database\Factories\PostFactory::new();
    }

    public function getUuidColumn(): string
    {
        return 'uuid';
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(70);
    }

    public function authorable () {
        return $this->morphTo();
    }
}
