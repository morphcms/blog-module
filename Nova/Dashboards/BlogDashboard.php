<?php

namespace Modules\Blog\Nova\Dashboards;

use Laravel\Nova\Dashboard;
use Modules\Blog\Enums\PostPermission;
use Modules\Blog\Nova\Metrics\PostsCount;
use Modules\Blog\Nova\Metrics\PostsPerCategory;
use Modules\Blog\Nova\Metrics\PostsPerDay;
use Modules\Blog\Nova\Metrics\UserPostsCount;

class BlogDashboard extends Dashboard
{
    public $name = 'Blog';

    public function cards()
    {
        return [
            PostsPerDay::make()->canSeeWhen(PostPermission::ViewAny->value),
            PostsCount::make()->canSeeWhen(PostPermission::ViewAny->value),
            UserPostsCount::make()->canSee(fn ($request) => $request->user()->canAny([PostPermission::ViewAny->value, PostPermission::ViewOwned->value])),
            // PostsPerCategory::make(),
        ];
    }
}
