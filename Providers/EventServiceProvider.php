<?php

namespace Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Blog\Listeners\RegisterBlogDashboards;
use Modules\Blog\Listeners\RegisterBlogNovaTool;
use Modules\Blog\Listeners\RegisterGraphQlSchemas;
use Modules\Blog\Listeners\RegisterPageBuilderTypes;
use Modules\Morphling\Events\BootModulesNovaDashboards;
use Modules\Morphling\Events\BootModulesNovaTools;
use Modules\PageBuilder\Events\BootPageBuilder;
use Nuwave\Lighthouse\Events\BuildSchemaString;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BootModulesNovaTools::class => [
            RegisterBlogNovaTool::class,
        ],

        BootModulesNovaDashboards::class => [
            RegisterBlogDashboards::class,
        ],

        BuildSchemaString::class => [
            RegisterGraphQlSchemas::class,
        ],

        BootPageBuilder::class => [
            RegisterPageBuilderTypes::class,
        ],

    ];
}
