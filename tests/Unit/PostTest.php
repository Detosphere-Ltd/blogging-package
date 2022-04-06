<?php

namespace Detosphere\BlogPackage\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Detosphere\BlogPackage\Tests\TestCase;
use Detosphere\BlogPackage\Models\Post;
use Detosphere\BlogPackage\Tests\User;
use Illuminate\Support\Str;

class PostTest extends TestCase
{
    use RefreshDatabase;

    function test_a_post_has_a_title()
    {
        $post = Post::factory()->state(['title' => 'Fake Title'])->create();
        $this->assertEquals('Fake Title', $post->title);
    }

    function test_slug_is_generated_automatically()
    {
        $post = Post::factory()->create(['title' => 'Fake Title']);
        $this->assertEquals(Str::slug('Fake Title'), $post->slug);
    }

    function test_a_post_has_an_author()
    {
        $post = Post::factory()->author()->create();
        $this->assertEquals('Detosphere\BlogPackage\Tests\User', $post->authorable_type);
        $this->assertEquals(1, $post->authorable_id);
    }

    function test_a_post_belongs_to_a_user()
    {
        $author = User::factory()->create();
        $author->posts()->create([
            'title' => 'My first fake post',
        ]);

        $this->assertCount(1, Post::all());
        $this->assertCount(1, $author->posts);

        // Using tap() to alias $author->posts()->first() to $post
        // To provide cleaner and grouped assertions
        tap($author->posts()->first(), function ($post) use ($author) {
            $this->assertEquals('My first fake post', $post->title);
            $this->assertTrue($post->authorable->is($author));
        });
    }
}
