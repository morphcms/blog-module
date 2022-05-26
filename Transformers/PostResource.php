<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Models\Post;
use Modules\Collection\Transformers\CollectionResource;
use Modules\PageBuilder\Transformers\ContentResource;

/**
 * @mixin Post
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => PostStatus::from($this->status),
            'summary' => $this->summary,
            'readTime' => $this->read_time,
            'collection' => $this->whenLoaded('collection', fn() => new CollectionResource($this->collection)),
            'collections' => $this->whenLoaded('collections', fn() => CollectionResource::collection($this->collections)),
            'content' => new ContentResource($this->content),
            'banner' => $this->getFirstMediaUrl('banner'),
        ];
    }
}
