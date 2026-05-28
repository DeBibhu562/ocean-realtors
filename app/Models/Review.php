<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    public const TARGET_SITE = 'site';

    public const TARGET_PROPERTY = 'property';

    public const TARGET_BLOG_POST = 'blog_post';

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'target_type',
        'target_id',
        'name',
        'email',
        'rating',
        'title',
        'body',
        'status',
        'ip_address',
        'approved_at',
        'approved_by',
        'rejected_reason',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'target_id' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'target_id');
    }

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'target_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeForTarget(Builder $query, string $targetType, ?int $targetId = null): Builder
    {
        return $query
            ->where('target_type', $targetType)
            ->when(
                $targetType === self::TARGET_SITE,
                fn (Builder $q) => $q->whereNull('target_id'),
                fn (Builder $q) => $q->where('target_id', $targetId)
            );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        $parts = collect(preg_split('/\s+/', trim($this->name)) ?: [])
            ->filter()
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->values()
            ->all();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'rating' => (int) $this->rating,
            'title' => $this->title,
            'body' => $this->body,
            'initials' => implode('', $parts) ?: '?',
            'created_at' => $this->created_at?->toIso8601String(),
            'created_at_formatted' => $this->created_at?->format('M j, Y'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return array_merge($this->toPublicArray(), [
            'email' => $this->email,
            'status' => $this->status,
            'target_type' => $this->target_type,
            'target_id' => $this->target_id,
            'target_label' => $this->targetLabel(),
            'target_url' => $this->targetUrl(),
            'rejected_reason' => $this->rejected_reason,
            'ip_address' => $this->ip_address,
        ]);
    }

    public function targetLabel(): string
    {
        return match ($this->target_type) {
            self::TARGET_SITE => 'Site testimonial',
            self::TARGET_PROPERTY => $this->property?->title ?? "Property #{$this->target_id}",
            self::TARGET_BLOG_POST => $this->blogPost?->title ?? "Blog post #{$this->target_id}",
            default => $this->target_type,
        };
    }

    public function targetUrl(): ?string
    {
        return match ($this->target_type) {
            self::TARGET_SITE => '/testimonials',
            self::TARGET_PROPERTY => $this->property
                ? route('properties.show', ['slug' => $this->property->slug])
                : null,
            self::TARGET_BLOG_POST => $this->blogPost
                ? route('blog.show', $this->blogPost)
                : null,
            default => null,
        };
    }

    public static function validTargetTypes(): array
    {
        return [
            self::TARGET_SITE,
            self::TARGET_PROPERTY,
            self::TARGET_BLOG_POST,
        ];
    }
}
