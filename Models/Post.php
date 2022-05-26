<?php

namespace Modules\Blog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use Modules\Blog\Database\Factories\PostFactory;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Utils\Table;
use Modules\Collection\Traits\HasCollections;
use Modules\Morphling\Traits\HasTranslatableSlug;
use Modules\PageBuilder\Traits\HasContents;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Post extends Model implements HasMedia
{

    use HasFactory,
        HasSlug,
        Searchable,
        HasTranslations,
        HasCollections,
        HasTranslatableSlug,
        InteractsWithMedia,
        HasContents;


    public array $translatable = ['title', 'summary', 'slug'];

    protected $guarded = [];

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }

    public function getTable(): string
    {
        return Table::posts();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->whereStatus(PostStatus::Published);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default');
        $this->addMediaCollection('banner')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->quality(70);
    }

    public function toSearchableArray(): array
    {
        $indexes = [];

        $contents = $this
            ->contents()
            ->published()
            ->get();


        try {
            foreach ($contents as $content) {
                $indexes['content_' . $content->locale] = $content->getIndexData();
            }
        } catch (\Exception $exception) {
                //
        }


        return [
            'title' => $this->title,
            'summary' => $this->summary,
            'status' => $this->status,
            ...$indexes,
        ];
    }


}
