<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Blog\Models\Post;
use Modules\Blog\Transformers\PostResource;

class IndexController extends Controller
{

    public function __invoke(): AnonymousResourceCollection
    {
        $posts = Post::query()->published()->with('collections')->get();

        return PostResource::collection($posts);
    }
}
