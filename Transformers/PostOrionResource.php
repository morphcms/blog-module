<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Request;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Models\Post;
use Modules\Collection\Transformers\CollectionResource;
use Modules\PageBuilder\Transformers\ContentResource;
use Modules\SeoSorcery\Transformers\SeoEntityResource;
use Orion\Http\Resources\Resource;

/**
 * @mixin Post
 */
class PostOrionResource extends Resource
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
            'collection' => $this->whenLoaded('collection', fn () => new CollectionResource($this->collection)),
            'collections' => $this->whenLoaded('collections', fn () => CollectionResource::collection($this->collections)),
            'content' => $this->whenLoaded('contentPublished', fn () => new ContentResource($this->contentPublished)),
            'contents' => $this->whenLoaded('contentsPublished', fn () => ContentResource::collection($this->contentsPublished)),
            'banner' => $this->whenLoaded('media', fn () => $this->getFirstMediaUrl('banner')),
            'seo' =>  $this->whenLoaded('seo', fn() => new SeoEntityResource($this->seo)),
        ];
    }
}
