<?php

namespace Modules\Blog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;
use Modules\Blog\Enums\PostPermission;
use Modules\Blog\Nova\Resources\Post;

class NovaBlogTool extends Tool
{
    private static array $resources = [
        Post::class,
    ];

    public function boot()
    {
        \Nova::resources(self::$resources);
    }

    public function menu(Request $request)
    {
        $menu = MenuSection::resource(Post::class)
            ->icon('pencil-alt')
            ->canSee(fn () => $request->user()->canAny([PostPermission::ViewAny->value, PostPermission::ViewOwned->value]));

        $menu->name = __('Blog');

        return $menu;
    }
}
