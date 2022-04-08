<?php

namespace DetosphereLtd\BlogPackage\Database\Factories;

use DetosphereLtd\BlogPackage\Models\Post;
use DetosphereLtd\BlogPackage\Tests\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    public function fakeJson()
    {
        // return json_encode([
        //     "time" => 1649346249899,
        //     "blocks" => [
        //         // header
        //         [
        //             "id" => "57bAwtKpmf",
        //             "type" => "header",
        //             "data" => [
        //                 "text" => "Editor.js",
        //                 "level" => 2
        //             ]
        //         ],
        //         // paragraph
        //         [
        //             "id" => "a298rcjpoW",
        //             "type" => "paragraph",
        //             "data" => [
        //                 "text" => "Hey. Meet the new Editor. On this page you can see it in action — try to edit this text."
        //             ]
        //         ],
        //         // header
        //         [
        //             "id" => "oe00TR5u0D",
        //             "type" => "header",
        //             "data" => [
        //                 "text" => "Key features",
        //                 "level" => 3
        //             ]
        //         ],
        //         // list
        //         [
        //             "id" => "MO-6h3PhKs",
        //             "type" => "list",
        //             "data" => [
        //                 "style" => "unordered",
        //                 "items" => [
        //                     "It is a block-styled editor",
        //                     "It returns clean data output in JSON",
        //                     "Designed to be extendable and pluggable with a simple API"
        //                 ]
        //             ]
        //         ],
        //         // header
        //         [
        //             "id" => "4aNZKcM336",
        //             "type" => "header",
        //             "data" => [
        //                 "text" => "What does it mean clean data output",
        //                 "level" => 3
        //             ]
        //         ],
        //         // paragraph
        //         [
        //             "id" => "g0KpsO6VW_",
        //             "type" => "paragraph",
        //             "data" => [
        //                 "text" => "Classic WYSIWYG-editors produce raw HTML-markup with both content data and content appearance. On the contrary, Editor.js outputs JSON object with data of each Block. You can see an example below"
        //             ]
        //         ],
        //         // image
        //         [
        //             "id" => "dmKs9UJFLx",
        //             "type" => "image",
        //             "data" => [
        //                 "file" => [
        //                     "url" => "https://codex.so/public/app/img/external/codex2x.png"
        //                 ],
        //                 "caption" => "",
        //                 "withBorder" => false,
        //                 "stretched" => false,
        //                 "withBackground" => false
        //             ]
        //         ]
        //     ],
        //     "version" => "2.23.1"
        // ]);

        return json_encode([
            "time" => 1649346249899,
            "blocks" => [
                // header
                [
                    "id" => "57bAwtKpmf",
                    "type" => "header",
                    "data" => [
                        "text" => "Editor.js",
                        "level" => 2
                    ]
                ]
            ],
            "version" => "2.23.1"
        ]);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'content' => $this->fakeJson()
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
