<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogWriter extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'photo',
        'bio',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function photoUrl(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (str_starts_with($this->photo, 'http') || str_starts_with($this->photo, '/')) {
            return $this->photo;
        }

        return '/storage/'.$this->photo;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photoUrl(),
            'bio' => $this->bio,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photoUrl(),
            'bio' => $this->bio ?? '',
            'sort_order' => $this->sort_order,
        ];
    }
}
