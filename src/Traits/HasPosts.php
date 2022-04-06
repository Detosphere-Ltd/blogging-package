<?php

namespace Detosphere\BlogPackage\Traits;

use Detosphere\BlogPackage\Models\Post;

trait HasPosts
{
    public function posts()
    {
        return $this->morphMany(Post::class, 'authorable');
    }
}