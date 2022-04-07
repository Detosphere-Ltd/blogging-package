<?php

namespace Detosphere\BlogPackage\Transformers;

use Detosphere\BlogPackage\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected array $availableIncludes = [];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'uuid' => $post->uuid,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'is_draft' => $post->is_draft,
            'scheduled_for' => $post->scheduled_for,
            'published_at' => $post->published_at,
            'created_at' => $post->created_at,
            'udpated_at' => $post->updated_at,
        ];
    }
}
