<?php

namespace Detosphere\BlogPackage\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Detosphere\BlogPackage\Models\Post;
use Detosphere\BlogPackage\Tests\TestCase;
use Detosphere\BlogPackage\Tests\User;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    function test_authenticated_users_can_create_a_post()
    {
        // To make sure we don't start with a Post
        Post::truncate();
        $this->assertCount(0, Post::all());

        /** @var \App\Models\User */
        $author = User::factory()->create();

        $response = $this->actingAs($author)->postJson(route('posts.store'), [
            'title' => 'My first fake title'
        ]);

        $response->assertCreated();

        $this->assertCount(1, Post::all());

        tap(Post::first(), function ($post) use ($response, $author) {
            $this->assertEquals('My first fake title', $post->title);
            $this->assertTrue($post->authorable->is($author));
        });
    }

    function test_guests_cannot_create_posts()
    {
        // We're starting from an unauthenticated state
        $this->assertFalse(auth()->check());

        $this->post(route('posts.store'), [
            'title' => 'A valid title'
        ])->assertForbidden();
    }
}
