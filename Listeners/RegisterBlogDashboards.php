<?php

namespace Modules\Blog\Listeners;

use Modules\Blog\Nova\Dashboards\BlogDashboard;
use Modules\Morphling\Events\BootModulesNovaDashboards;

class RegisterBlogDashboards
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BootModulesNovaDashboards  $event
     * @return array
     */
    public function handle(BootModulesNovaDashboards $event): array
    {
        if (! config('blog.enable_dashboard', false)) {
            return [];
        }

        return [
            BlogDashboard::make(), // TODO: implement permissions
        ];
    }
}
