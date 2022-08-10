<?php

namespace Modules\Blog\Listeners;

use Modules\Blog\Nova\Resources\Post;
use Modules\PageBuilder\Events\BootPageBuilder;

class RegisterPageBuilderTypes
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
     * @param  BootPageBuilder  $event
     * @return void
     */
    public function handle(BootPageBuilder $event)
    {
        $event->pageBuilder->types([
            Post::class,
        ]);
    }
}
