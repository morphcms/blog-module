<?php

namespace Modules\Blog\Listeners;

use Modules\Blog\Nova\NovaBlogTool;

class RegisterBlogNovaTool
{
    public function __invoke(): array
    {
        return [
            NovaBlogTool::make(),
        ];
    }
}
