<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Agent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'whatsapp_phone',
        'avatar',
        'designation',
        'bio',
        'city',
        'rating',
        'reviews_count',
        'experience_years',
        'languages',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
            'reviews_count' => 'integer',
            'experience_years' => 'integer',
            'languages' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Agent $agent) {
            if (empty($agent->slug)) {
                $agent->slug = static::uniqueSlug($agent->name);
            }
        });

        static::updating(function (Agent $agent) {
            if ($agent->isDirty('name') && ! $agent->isDirty('slug')) {
                $agent->slug = static::uniqueSlug($agent->name, $agent->id);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
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

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getListedCountAttribute(): int
    {
        if ($this->properties_count !== null) {
            return (int) $this->properties_count;
        }

        return $this->properties()->count();
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http') || str_starts_with($this->avatar, '/')) {
            return $this->avatar;
        }

        return '/storage/'.$this->avatar;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_phone' => $this->whatsapp_phone ?? $this->phone,
            'avatar' => $this->avatar_url ?? $this->defaultAvatarUrl(),
            'designation' => $this->designation ?? 'Property Consultant',
            'bio' => $this->bio,
            'city' => $this->city,
            'rating' => (float) $this->rating,
            'reviews_count' => (int) $this->reviews_count,
            'experience_years' => $this->experience_years,
            'languages' => $this->languages ?? [],
            'listed_count' => $this->listed_count,
        ];
    }

    /**
     * Public profile payload without contact numbers (lead-gated on agent show page).
     *
     * @return array<string, mixed>
     */
    public function toPublicProfileArray(): array
    {
        $data = $this->toPublicArray();
        unset($data['phone'], $data['whatsapp_phone']);
        $data['has_contact'] = filled($this->phone) || filled($this->whatsapp_phone);

        return $data;
    }

    public function defaultAvatarUrl(): string
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=1a56db&color=fff&size=160';
    }
}
