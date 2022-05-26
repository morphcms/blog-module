<?php

namespace Modules\Blog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;
use Modules\Blog\Nova\Resources\Post;

class NovaBlogTool extends Tool
{

    public function boot()
    {
        \Nova::resources([
            Post::class,
        ]);
    }

    public function menu(Request $request)
    {
       return MenuSection::make('Blog', [
           MenuItem::resource(Post::class)->canSee(fn() => true),
       ])->icon('pencil-alt')->collapsable();
    }
}
