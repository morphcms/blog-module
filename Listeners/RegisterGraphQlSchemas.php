<?php

namespace Modules\Blog\Listeners;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Nuwave\Lighthouse\Events\BuildSchemaString;
use Nuwave\Lighthouse\Schema\Source\SchemaStitcher;

class RegisterGraphQlSchemas
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
     * @param BuildSchemaString $event
     * @return string
     * @throws FileNotFoundException
     */
    public function handle(BuildSchemaString $event): string
    {
        $stitcher = new SchemaStitcher(module_path('Blog', 'graphql/schema.graphql'));
        return $stitcher->getSchemaString();
    }
}
