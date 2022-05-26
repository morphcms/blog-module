<?php

namespace Modules\Blog\Nova\Resources;

use App\Nova\Resource;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Utils\Table;
use Modules\Collection\Nova\Resources\Collection;
use Modules\Morphling\Nova\Actions\UpdateStatus;
use Modules\Morphling\Nova\Filters\ByStatus;
use Modules\PageBuilder\Traits\HasContentsNova;

class Post extends Resource
{
    use HasContentsNova, HasTabs;

    public static string $model = \Modules\Blog\Models\Post::class;

    public static $displayInNavigation = false;

    public static $title = 'title';

    public static $search = [
        'title', 'summary', 'status'
    ];
    

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Stack::make('Title', [
                Line::make('Title')->asHeading(),
                Line::make('Slug')->asBase()
            ])->sortable()->exceptOnForms(),

            Text::make('Title')
                ->onlyOnForms()
                ->rulesFor('en', ['required'])
                ->translatable(),

            Slug::make('Slug')
                ->from('Title')
                ->onlyOnForms()
                ->translatable()
                ->rulesFor('en', ['required'])
                ->rules(['max:180', Rule::unique(Table::posts(), 'slug')->ignoreModel($this->model())]),

            Badge::make('Status')
                ->displayUsing(fn() => PostStatus::from($this->status)->value)
                ->map(PostStatus::getNovaBadgeColors())
                ->exceptOnForms(),

            Textarea::make('Summary')
                ->help(__('A short and descriptive text about this post. Maximum of 200 characters.'))
                ->rows(2)
                ->rulesFor('en', ['required'])
                ->rules(['max:200'])
                ->translatable(),

            Images::make('Image', 'banner'),

            Tabs::make('Relations', [
                $this->contentsField(),
                MorphedByMany::make('Collections', 'collections', Collection::class)
                    ->searchable(),
            ])
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            UpdateStatus::make(PostStatus::class),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            ByStatus::make(PostStatus::class),
        ];
    }
}
