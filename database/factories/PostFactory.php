<?php

namespace Detosphere\BlogPackage\Database\Factories;

use Detosphere\BlogPackage\Models\Post;
use Detosphere\BlogPackage\Tests\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
        ];
    }

    /**
     * Specify the author of the post.
     * @param String $authorable_id
     * @param String $authorable_type
     *
     * @return array
     */
    public function author($authorable_id = null, $authorable_type = null) {
        return $this->state(function(array $attributes) use ($authorable_id, $authorable_type) {
            // if params weren't given, generate a user
            if (!(isset($authorable_id)) || !(isset($authorable_type))) {
                $author = User::factory()->create();
                $authorable_id = $author->id;
                $authorable_type = get_class($author);
            }

            return [
                'authorable_id' => $authorable_id,
                'authorable_type' => $authorable_type,
            ];
        });
    }
}
