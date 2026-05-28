<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'price', 'status', 'type', 'address', 'city', 'image',
        'bedrooms', 'bathrooms', 'garage', 'size', 'is_featured', 'user_id',
        'category', 'listing_type', 'listing_status', 'bhk', 'society_name', 'built_up_area', 'carpet_area',
        'area', 'locality',
        'age_of_property', 'balconies', 'furnish_type', 'amenities', 'covered_parking',
        'open_parking', 'tenant_type', 'bachelor_preference', 'pet_friendly', 'available_from',
        'maintenance_charges', 'maintenance_type', 'security_deposit', 'lock_in_period',
        'booking_amount', 'price_negotiable', 'food_charges', 'pg_food_included',
        'brokerage_charge', 'brokerage_negotiable', 'parking_charges_type', 'painting_charges',
        'floor_no', 'total_floors', 'facing', 'servant_room', 'rera_id', 'highlights', 'photos',
        'latitude', 'longitude', 'virtual_tour_url', 'floor_plan_image', 'views_count',
        'agent_id', 'slug', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'amenities' => 'array',
        'highlights' => 'array',
        'photos' => 'array',
        'available_from' => 'date',
        'is_featured' => 'boolean',
        'pet_friendly' => 'boolean',
        'price_negotiable' => 'boolean',
        'pg_food_included' => 'boolean',
        'brokerage_negotiable' => 'boolean',
        'servant_room' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function priceDropAlerts()
    {
        return $this->hasMany(PriceDropAlert::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug) && ! empty($property->title)) {
                $property->slug = static::uniqueSlug($property->title);
            }
        });

        static::updating(function (Property $property) {
            if ($property->isDirty('title') && ! $property->isDirty('slug') && ! empty($property->title)) {
                $property->slug = static::uniqueSlug($property->title, $property->id);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'property';
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
}
