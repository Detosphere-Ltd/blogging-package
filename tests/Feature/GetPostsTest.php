<?php

namespace Detosphere\BlogPackage\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Detosphere\BlogPackage\Models\Post;
use Detosphere\BlogPackage\Tests\TestCase;
use Detosphere\BlogPackage\Tests\User;
use Illuminate\Testing\Fluent\AssertableJson;

class GetPostsTest extends TestCase
{
    use RefreshDatabase;

    function test_authenticated_users_can_view_all_posts()
    {
        // To make sure we don't start with a Post
        Post::truncate();
        $this->assertCount(0, Post::all());

        /** @var \App\Models\User */
        $user = User::factory()->create();

        $posts = Post::factory(3)->create();

        $this->actingAs($user)
            ->getJson(route('posts.index'))
            ->assertSuccessful()
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'data', 'meta'])
                    ->where('message', 'Successfully returned all posts.');
            });
    }

    function test_authenticated_users_can_view_single_post()
    {
        // To make sure we don't start with a Post
        Post::truncate();
        $this->assertCount(0, Post::all());

        /** @var \App\Models\User */
        $user = User::factory()->create();

        Post::factory(3)->create();
        $post = Post::first();

        $response = $this->actingAs($user)
            ->getJson(route('posts.show', ['post' => $post->uuid]))
            ->assertSuccessful()
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->where('message', 'Successfully returned post.');
            });
    }
}
