<?php

namespace DetosphereLtd\BlogPackage\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DetosphereLtd\BlogPackage\Models\Post;
use DetosphereLtd\BlogPackage\Tests\TestCase;
use DetosphereLtd\BlogPackage\Tests\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    function test_authenticated_users_can_create_a_post_without_publishing()
    {
        $this->withExceptionHandling();

        // To make sure we don't start with a Post
        Post::truncate();
        $this->assertCount(0, Post::all());

        $postToCreate = Post::factory()->make();

        /** @var \App\Models\User */
        $author = User::factory()->create();

        $response = $this->actingAs($author)->postJson(route('posts.store'), [
            'title' => $postToCreate->title,
            'publish' => false,
            'content' => json_decode($postToCreate->content)
        ]);

        $response->assertCreated()
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->where('message', 'Post created.');
            });
        $this->assertCount(1, Post::all());

        tap(Post::first(), function ($post) use ($response, $author, $postToCreate) {
            $this->assertEquals($postToCreate->title, $post->title);
            $this->assertEquals($postToCreate->content, $post->content);
            $this->assertTrue($post->authorable->is($author));
        });

        // dd(DB::table('posts')->get());

        $this->assertDatabaseCount('posts', 1);
        // perform a strict database record match
        $this->assertDatabaseHas('posts', [
            // use Eloquent since PostFactory does not have id and uuid
            'id' => Post::first()->id,
            'uuid' => Post::first()->uuid,
            'title' => $postToCreate->title,
            'slug' => Post::first()->slug,
            // TODO Excerpt should be auto generated
            'excerpt' => null,
            // TODO A post should automatically be a draft?
            'is_draft' => true,
            'content' => $postToCreate->content,
            'authorable_type' => get_class($author),
            'authorable_id' => $author->id,
            // no editor at this point
            'editorable_type' => null,
            'editorable_id' => null,
            'scheduled_for' => null,
            'published_at' => null,
            'created_at' => Post::first()->created_at->format('Y-m-d H:i:s'),
            'updated_at' => Post::first()->updated_at->format('Y-m-d H:i:s'),
        ]);        
    }

    function test_authenticated_users_can_create_a_post_to_be_published_later()
    {
        $this->withExceptionHandling();

        // To make sure we don't start with a Post
        Post::truncate();
        $this->assertCount(0, Post::all());

        $postToCreate = Post::factory()->make();

        /** @var \App\Models\User */
        $author = User::factory()->create();

        // TODO Do not hardcode the dates
        // a future date
        $timeToPublishAt = 1652137688;

        $response = $this->actingAs($author)->postJson(route('posts.store'), [
            'title' => $postToCreate->title,
            'publish' => true,
            'publishing_at' => $timeToPublishAt,
            'content' => json_decode($postToCreate->content)
        ]);

        $response->assertCreated()
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->where('message', 'Post created.');
            });
            // dd($response->json());
        $this->assertCount(1, Post::all());

        tap(Post::first(), function ($post) use ($response, $author, $postToCreate) {
            $this->assertEquals($postToCreate->title, $post->title);
            $this->assertEquals($postToCreate->content, $post->content);
            $this->assertTrue($post->authorable->is($author));
        });

        // dd(DB::table('posts')->get());

        $this->assertDatabaseCount('posts', 1);
        // perform a strict database record match
        $this->assertDatabaseHas('posts', [
            // use Eloquent since PostFactory does not have id and uuid
            'id' => Post::first()->id,
            'uuid' => Post::first()->uuid,
            'title' => $postToCreate->title,
            'slug' => Post::first()->slug,
            // TODO Excerpt should be auto generated
            'excerpt' => null,
            // TODO A post should automatically be a draft?
            'is_draft' => false,
            'content' => $postToCreate->content,
            'authorable_type' => get_class($author),
            'authorable_id' => $author->id,
            // no editor at this point
            'editorable_type' => null,
            'editorable_id' => null,
            'scheduled_for' => Carbon::parse($timeToPublishAt)->toDateTimeString(),
            'published_at' => null,
            'created_at' => Post::first()->created_at->format('Y-m-d H:i:s'),
            'updated_at' => Post::first()->updated_at->format('Y-m-d H:i:s'),
        ]);        
    }

    function test_authenticated_users_can_create_a_post_to_be_published_now()
    {
        $this->withExceptionHandling();

        // To make sure we don't start with a Post
        Post::truncate();
        $this->assertCount(0, Post::all());

        $postToCreate = Post::factory()->make();

        /** @var \App\Models\User */
        $author = User::factory()->create();

        // TODO Do not hardcode the dates
        // a past date
        $timeToPublishAt = 1649546443;

        $response = $this->actingAs($author)->postJson(route('posts.store'), [
            'title' => $postToCreate->title,
            'publish' => true,
            'publishing_at' => $timeToPublishAt,
            'content' => json_decode($postToCreate->content)
        ]);

        $response->assertCreated()
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->where('message', 'Post created.');
            });
        $this->assertCount(1, Post::all());

        tap(Post::first(), function ($post) use ($response, $author, $postToCreate) {
            $this->assertEquals($postToCreate->title, $post->title);
            $this->assertEquals($postToCreate->content, $post->content);
            $this->assertTrue($post->authorable->is($author));
        });

        // dd(DB::table('posts')->get());

        $this->assertDatabaseCount('posts', 1);
        // perform a strict database record match
        $this->assertDatabaseHas('posts', [
            // use Eloquent since PostFactory does not have id and uuid
            'id' => Post::first()->id,
            'uuid' => Post::first()->uuid,
            'title' => $postToCreate->title,
            'slug' => Post::first()->slug,
            // TODO Excerpt should be auto generated
            'excerpt' => null,
            // TODO A post should automatically be a draft?
            'is_draft' => false,
            'content' => $postToCreate->content,
            'authorable_type' => get_class($author),
            'authorable_id' => $author->id,
            // no editor at this point
            'editorable_type' => null,
            'editorable_id' => null,
            'scheduled_for' => null,
            'published_at' => Carbon::parse($timeToPublishAt)->toDateTimeString(),
            'created_at' => Post::first()->created_at->format('Y-m-d H:i:s'),
            'updated_at' => Post::first()->updated_at->format('Y-m-d H:i:s'),
        ]);        
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
