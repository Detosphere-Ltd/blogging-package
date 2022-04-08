<?php

namespace DetosphereLtd\BlogPackage\Traits;

use DetosphereLtd\BlogPackage\Models\Post;

trait HasPosts
{
    public function posts()
    {
        return $this->morphMany(Post::class, 'authorable');
    }
}