<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'user_id',
        'blog_writer_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
        'noindex',
        'reading_time_minutes',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'noindex' => 'boolean',
            'reading_time_minutes' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post) {
            if ($post->isDirty('slug') || empty($post->slug)) {
                $normalized = static::normalizeSlug($post->slug, $post->title);
                if ($normalized !== '') {
                    $post->slug = $normalized;
                }
            }
        });

        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = static::uniqueSlug($post->title);
            }
        });

        static::updating(function (BlogPost $post) {
            if ($post->isDirty('title') && ! $post->isDirty('slug')) {
                $post->slug = static::uniqueSlug($post->title, $post->id);
            }
        });
    }

    public static function normalizeSlug(?string $slug, ?string $title = null): string
    {
        if ($slug !== null && trim($slug) !== '') {
            return Str::slug(trim($slug, " \t\n\r\0\x0B/"));
        }

        return $title ? Str::slug($title) : '';
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    public static function estimateReadingTime(string $html): int
    {
        $text = trim(strip_tags($html));
        $words = $text === '' ? 0 : str_word_count($text);

        return max(1, (int) ceil($words / 200));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blogWriter(): BelongsTo
    {
        return $this->belongsTo(BlogWriter::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function authorForDisplay(): array
    {
        if ($this->relationLoaded('blogWriter') && $this->blogWriter) {
            return $this->blogWriter->toPublicArray();
        }

        if ($this->blogWriter) {
            return $this->blogWriter->toPublicArray();
        }

        return [
            'id' => null,
            'name' => 'Ocean Realtors',
            'photo' => null,
            'bio' => null,
        ];
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'target_id')
            ->where('target_type', Review::TARGET_BLOG_POST);
    }

    public function scopePublished($query)
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at !== null;
    }

    public function publicUrl(): string
    {
        return url('/blog/'.ltrim($this->slug, '/'));
    }

    public function getExcerptAttribute(?string $value): string
    {
        if ($value) {
            return $value;
        }

        $plain = trim(strip_tags($this->content ?? ''));

        return Str::limit($plain, 160);
    }

    public function featuredImageUrl(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http') || str_starts_with($this->featured_image, '/')) {
            return $this->featured_image;
        }

        return '/storage/'.$this->featured_image;
    }

    public function ogImageUrl(): ?string
    {
        if ($this->og_image) {
            return str_starts_with($this->og_image, 'http') || str_starts_with($this->og_image, '/')
                ? $this->og_image
                : '/storage/'.$this->og_image;
        }

        return $this->featuredImageUrl();
    }

    /**
     * @return array<string, mixed>
     */
    public function toListArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featuredImageUrl(),
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'reading_time_minutes' => $this->reading_time_minutes,
            'author' => $this->authorForDisplay(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        $canonical = $this->canonical_url ?: $this->publicUrl();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $this->featuredImageUrl(),
            'published_at' => $this->published_at?->toIso8601String(),
            'published_at_formatted' => $this->published_at?->format('M j, Y'),
            'reading_time_minutes' => $this->reading_time_minutes,
            'author' => $this->authorForDisplay(),
            'seo' => [
                'title' => $this->meta_title ?: $this->title,
                'description' => $this->meta_description ?: $this->excerpt,
                'keywords' => $this->meta_keywords,
                'og_image' => $this->ogImageUrl(),
                'canonical' => $canonical,
                'noindex' => (bool) $this->noindex,
            ],
        ];
    }
}
