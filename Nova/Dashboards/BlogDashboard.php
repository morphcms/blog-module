<?php

namespace Modules\Blog\Nova\Dashboards;

use Laravel\Nova\Dashboard;
use Modules\Blog\Nova\Metrics\PostsCount;
use Modules\Blog\Nova\Metrics\PostsPerCategory;
use Modules\Blog\Nova\Metrics\PostsPerDay;

class BlogDashboard extends Dashboard
{
    public $name = 'Blog';

    public function cards()
    {
        return [
            PostsPerDay::make(),
            PostsCount::make(),
           // PostsPerCategory::make(),
        ];
    }
}
