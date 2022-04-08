<?php

namespace DetosphereLtd\BlogPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DetosphereLtd\BlogPackage\Traits\HasUuid;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasSlug, HasUuid;

    protected $guarded = ['id', 'uuid'];

    protected static function newFactory()
    {
        return \DetosphereLtd\BlogPackage\Database\Factories\PostFactory::new();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_draft' => 'boolean',
    ];

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
