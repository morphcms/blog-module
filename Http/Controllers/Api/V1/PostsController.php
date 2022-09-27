<?php

namespace Modules\Blog\Http\Controllers\Api\V1;

use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\Http\Requests\PostOrionRequest;
use Modules\Blog\Models\Post;
use Modules\Blog\Transformers\PostOrionResource;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class PostsController extends Controller
{
    protected $model = Post::class;

    protected $resource = PostOrionResource::class;

    protected $request = PostOrionRequest::class;

    protected function keyName(): string
    {
        $locale = app()->getLocale();

        return "slug";
    }

    public function includes(): array
    {
        return [
            'collections',
            'collection',
            'contentPublished',
            'contentsPublished',
            'media',
            'author',
            'seo',
        ];
    }

    public function exposedScopes(): array
    {
        return [
            'latest',
            'whereInCollections',
            'whereInCollectionsBySlug',
        ];
    }

    public function searchableBy(): array
    {
        $locale = app()->getLocale();

        if (count(config('nova-translatable.locales')) > 1) {
            return ["title->$locale", "slug->$locale", "summary->$locale"];
        } else {
            return ["title", "slug", "summary"];
        }
    }

    public function sortableBy(): array
    {
        $locale = app()->getLocale();

        if (count(config('nova-translatable.locales')) > 1) {
            return [
                'id',
                "title->$locale",
            ];
        } else {
            return [
                'id',
                'title',
            ];
        }
    }

    /**
     * Builds Eloquent query for fetching entities in index method.
     *
     * @param Request $request
     * @param array $requestedRelations
     * @return Builder
     */
    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);

        $query->published();

        return $query;
    }
}
