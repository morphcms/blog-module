<?php

namespace Modules\Blog\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Blog\Models\Post;
use Tests\TestCase;

class Posts extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @test
     *
     * @return void
     */
    public function guest_can_fetch_published_records()
    {
        Post::factory(3)->published()->create();
        Post::factory(1)->draft()->create();

        $this->assertDatabaseCount(Post::class, 4);

        $response = $this->getJson('/api/blog/v1/posts');
        $response->assertOk();

        $this->assertCount(3, $response->json('data'), 'JSON Records');
    }

    /**
     * @test
     *
     * @return void
     */
    public function guest_can_fetch_one_published_record()
    {
        Post::factory(2)->draft()->create();
        $post = Post::factory(1)->published()->create();

        $this->assertDatabaseCount(Post::class, 3);

        $response = $this->getJson('/api/blog/v1/posts/1');
        $response->assertOk();

        // $response->assertSee($post->title);
    }
}
