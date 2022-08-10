<?php

namespace Modules\Blog\Utils;

class Table
{
    public static function prefix($table): string
    {
        return config('blog.table_prefix').$table;
    }

    public static function posts(): string
    {
        return static::prefix('posts');
    }
}
