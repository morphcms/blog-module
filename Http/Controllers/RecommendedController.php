<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Blog\Models\Post;
use Modules\Blog\Transformers\RecommededPostResource;
use Modules\Collection\Utils\Table;

class RecommendedController extends Controller
{
    public function __invoke(Post $post): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->published()
            ->with('collections')
            ->whereNot('id', $post->id)
            ->whereHas('collections', function ($query) use ($post) {
                $query->whereIn(Table::collectionables().'.collection_id', $post->collections->pluck('id')->toArray());
            })
            ->limit(3)
            ->inRandomOrder()
            ->get();

        return RecommededPostResource::collection($posts);
    }
}
