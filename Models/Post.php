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
use Modules\Morphling\Contracts\CanBeOwned;
use Modules\Morphling\Traits\HasOwner;
use Modules\Morphling\Traits\HasTranslatableSlug;
use Modules\PageBuilder\Traits\HasContents;
use Modules\SeoSorcery\Contracts\ICanBeSeoAnalyzed;
use Modules\SeoSorcery\Traits\HasSeo;
use Modules\SeoSorcery\Utils\SeoOptions;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * @method Post whereOwnedBy(string|int $id)
 * @method Post whereNullOrOwnedBy(string|int $id)
 */
class Post extends Model implements HasMedia, CanBeOwned, ICanBeSeoAnalyzed
{
    use HasFactory,
        HasSlug,
        Searchable,
        HasTranslations,
        HasCollections,
        HasTranslatableSlug,
        InteractsWithMedia,
        HasContents,
        HasSeo,
        HasOwner;

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

    /**
     * Alias for user relation
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
        $this->addMediaConversion('webp')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('banner', 'default');

        $this->addMediaConversion('thumb')
            ->format(Manipulations::FORMAT_WEBP)
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

    function setSeoOptions(): SeoOptions
    {
        return SeoOptions::make($this)
            ->setThumbnailCollection('banner');
    }
}
