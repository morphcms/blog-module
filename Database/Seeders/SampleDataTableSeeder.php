<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Blog\Models\Post;
use Modules\Collection\Models\Collection;

class SampleDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Post::factory(30)->create()->each(function(Post $post) {
            $collections = Collection::query()
                ->inRandomOrder()
                ->root()
                ->take(random_int(1, 3))
                ->pluck('id')
                ->toArray();

            $post->collections()->attach($collections);
        });

    }
}
