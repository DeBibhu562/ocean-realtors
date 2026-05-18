<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'price', 'status', 'type', 'address', 'city', 'image',
        'bedrooms', 'bathrooms', 'garage', 'size', 'is_featured', 'user_id',
        'category', 'listing_type', 'bhk', 'society_name', 'built_up_area', 'carpet_area',
        'age_of_property', 'balconies', 'furnish_type', 'amenities', 'covered_parking',
        'open_parking', 'tenant_type', 'bachelor_preference', 'pet_friendly', 'available_from',
        'maintenance_charges', 'maintenance_type', 'security_deposit', 'lock_in_period',
        'brokerage_charge', 'brokerage_negotiable', 'parking_charges_type', 'painting_charges',
        'floor_no', 'total_floors', 'facing', 'servant_room', 'rera_id', 'highlights', 'photos',
        'latitude', 'longitude', 'virtual_tour_url', 'floor_plan_image', 'views_count',
    ];

    protected $casts = [
        'amenities' => 'array',
        'highlights' => 'array',
        'photos' => 'array',
        'available_from' => 'date',
        'is_featured' => 'boolean',
        'pet_friendly' => 'boolean',
        'brokerage_negotiable' => 'boolean',
        'servant_room' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function priceDropAlerts()
    {
        return $this->hasMany(PriceDropAlert::class);
    }
}
