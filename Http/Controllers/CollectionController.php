<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Blog\Models\Post;
use Modules\Blog\Transformers\PostResource;
use Modules\Collection\Models\Collection;

class CollectionController extends Controller
{
    public function __invoke(Collection $collection): AnonymousResourceCollection
    {
        $posts = Post::query()->with('collections')->whereHas('collections', function ($query) use ($collection) {
            $query->where('slug->en', $collection->slug)->orWhere('slug->ro', $collection->slug);
        })->get();

        return PostResource::collection($posts);
    }
}
