<?php

namespace Modules\Blog\Nova\Resources;

use App\Nova\Resource;
use App\Nova\User;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Blog\Enums\PostPermission;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Nova\Metrics\PostsCount;
use Modules\Blog\Nova\Metrics\PostsPerDay;
use Modules\Blog\Nova\Metrics\UserPostsCount;
use Modules\Blog\Utils\Table;
use Modules\Collection\Traits\HasCollectionsNova;
use Modules\Morphling\Nova\Actions\UpdateStatus;
use Modules\Morphling\Nova\Filters\ByStatus;
use Modules\PageBuilder\Traits\HasContentsNova;
use Modules\SeoSorcery\Traits\HasSeoNova;

class Post extends Resource
{
    use HasContentsNova, HasTabs, HasSeoNova, HasCollectionsNova;

    public static string $model = \Modules\Blog\Models\Post::class;

    public static $displayInNavigation = false;

    public static $title = 'title';

    public static $search = [
        'title', 'summary', 'status',
    ];

    public static $with = ['user'];

    public function fields(NovaRequest $request)
    {
        $defaultLocale = config('app.locale', 'en');

        return [
            ID::make()->sortable(),

            Images::make(__('Banner'), 'banner')
                ->conversionOnIndexView('thumb')
                ->conversionOnPreview('thumb')
                ->conversionOnDetailView('webp')
                ->conversionOnForm('webp'),

            BelongsTo::make(__('Author'), 'user', User::class)
                // ->default(fn() => $request->user()->getKey()) // FIXME: This does not set the value when creating. It's a bug from Laravel Nova
                // ->readonly(fn() => $request->user()->cannot(PostPermission::ViewAny->value))
                ->nullable()
                ->searchable()
                ->sortable()
                ->filterable()
                ->showCreateRelationButton(),

            Stack::make(__('Title'), [
                Line::make('Title')->asHeading(),
                Line::make('Slug')->asBase(),
            ])->sortable()->exceptOnForms(),

            Text::make(__('Title'), 'title')
                ->onlyOnForms()
                ->translatable()
                ->rulesFor($defaultLocale, ['required'])
                ->rules(['nullable']),

            Slug::make(__('Slug'), 'slug')
                ->from("title.{$defaultLocale}")
                ->onlyOnForms()
                ->translatable()
                ->rulesFor($defaultLocale, ['required'])
                ->rules(['max:180', Rule::unique(Table::posts(), 'slug')->ignoreModel($this->model())]),

            Badge::make(__('Status'), 'status')
                ->displayUsing(fn () => PostStatus::from($this->status)->value)
                ->map(PostStatus::getNovaBadgeColors())
                ->exceptOnForms(),

            Textarea::make(__('Summary'), 'summary')
                ->help(__('A short and descriptive text about this post. Maximum of 200 characters.'))
                ->rows(2)
                ->translatable()
                ->rulesFor($defaultLocale, ['required'])
                ->rules(['max:200', 'nullable']),

            Tabs::make(__('Relations'), [
                $this->contentsField(),
                $this->collectionsField(),
                $this->seoField(),
            ]),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query): \Illuminate\Database\Eloquent\Builder
    {
        $user = $request->user();

        if ($user->cannot(PostPermission::ViewAny->value) && $user->can(PostPermission::ViewOwned->value)) {
            return $query->whereOwnedBy($user->getKey());
        }

        return $query;
    }

    public function cards(NovaRequest $request): array
    {
        return [
            PostsPerDay::make()->canSeeWhen(PostPermission::ViewAny->value),
            PostsCount::make()->canSeeWhen(PostPermission::ViewAny->value),
            UserPostsCount::make()->canSee(fn ($request) => $request->user()->canAny([PostPermission::ViewAny->value, PostPermission::ViewOwned->value])),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            UpdateStatus::make(PostStatus::class)->showInline(),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            ByStatus::make(PostStatus::class),
        ];
    }
}
