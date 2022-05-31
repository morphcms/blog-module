<?php

namespace Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Blog\Listeners\RegisterGraphQlSchemas;
use Nuwave\Lighthouse\Events\BuildSchemaString;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildSchemaString::class => [
            RegisterGraphQlSchemas::class,
        ],
    ];
}
