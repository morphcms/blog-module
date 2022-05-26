<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Routing\Controller;
use JetBrains\PhpStorm\Pure;
use Modules\Blog\Models\Post;
use Modules\Blog\Transformers\PostResource;

class ShowController extends Controller
{
    #[Pure] public function __invoke(Post $post): PostResource
    {
        return new PostResource($post);
    }
}
