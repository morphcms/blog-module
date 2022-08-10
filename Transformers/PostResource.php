<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Models\Post;
use Modules\Collection\Transformers\CollectionResource;
use Modules\PageBuilder\Transformers\ContentResource;
use Modules\SeoSorcery\Transformers\SeoEntityResource;

/**
 * @deprecated Use Orion Resource instead
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
            'banner' => $this->getFirstMediaUrl('banner'),
            'seo' => $this->whenLoaded('seo', fn () => new SeoEntityResource($this->seo)),
            'collection' => $this->whenLoaded('collection', fn () => new CollectionResource($this->collection)),
            'collections' => $this->whenLoaded('collections', fn () => CollectionResource::collection($this->collections)),
            'content' => new ContentResource($this->content),
        ];
    }
}
