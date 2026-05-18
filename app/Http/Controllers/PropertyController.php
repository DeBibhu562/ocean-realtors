<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\SystemSetting;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PropertyController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $properties = Property::with('user')->latest()->get();
        $watermark = SystemSetting::get('watermark_path');
        
        return Inertia::render('Dashboard', [
            'properties' => $properties,
            'watermark' => $watermark ? '/storage/' . $watermark : null
        ]);
    }

    public function publicIndex()
    {
        return Inertia::render('Properties/Index');
    }

    public function show(Property $property)
    {
        $property->load('user');

        if (\Illuminate\Support\Facades\Schema::hasColumn('properties', 'views_count')) {
            $property->increment('views_count');
            $property->refresh();
        }

        return Inertia::render('Properties/Show', [
            'property' => $this->transformPropertyForPage($property),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function transformPropertyForPage(Property $property): array
    {
        $user = $property->user;
        $photos = is_array($property->photos) ? $property->photos : [];
        $gallery = array_values(array_unique(array_filter(array_merge(
            $property->image ? [$property->image] : [],
            $photos
        ))));

        if ($gallery === []) {
            $gallery = ['https://via.placeholder.com/1200x675?text=No+Image'];
        }

        $lat = $property->latitude;
        $lng = $property->longitude;
        if ($lat === null || $lng === null) {
            $lat = 28.6139;
            $lng = 77.2090;
        }

        $area = (float) ($property->built_up_area ?? $property->size ?? 0);
        $isRental = strtolower((string) $property->listing_type) === 'rent'
            || str_contains(strtolower((string) $property->status), 'rent');

        return [
            'id' => $property->id,
            'title' => $property->title,
            'description' => $property->description,
            'price' => (float) $property->price,
            'status' => $property->status,
            'listing_type' => $property->listing_type,
            'type' => $property->type,
            'address' => $property->address,
            'city' => $property->city,
            'bedrooms' => (int) $property->bedrooms,
            'bathrooms' => (int) $property->bathrooms,
            'size' => (float) $property->size,
            'built_up_area' => $property->built_up_area !== null ? (float) $property->built_up_area : null,
            'amenities' => $property->amenities ?? [],
            'highlights' => $property->highlights ?? [],
            'gallery' => $gallery,
            'virtual_tour_url' => $property->virtual_tour_url,
            'floor_plan_image' => $property->floor_plan_image,
            'latitude' => (float) $lat,
            'longitude' => (float) $lng,
            'views_count' => (int) ($property->views_count ?? 0),
            'posted_at' => $property->created_at?->toIso8601String(),
            'is_rental' => $isRental,
            'area_display' => $area,
            'agent' => [
                'name' => $user?->name ?? 'Ocean Realtors',
                'email' => $user?->email,
                'phone' => $user?->phone ?? '+911124000000',
                'whatsapp_phone' => $user?->whatsapp_phone ?? $user?->phone ?? '+911124000000',
                'avatar' => $user?->avatar,
                'designation' => $user?->designation ?? 'Senior Property Consultant',
                'rating' => 4.8,
                'reviews_count' => 127,
                'listed_count' => $user ? $user->properties()->count() : 0,
            ],
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'required|string',
            'type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'image' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'garage' => 'nullable|integer',
            'size' => 'nullable|integer',
            'is_featured' => 'boolean',
            'category' => 'required|string',
            'listing_type' => 'required|string',
            'bhk' => 'nullable|string',
            'society_name' => 'nullable|string',
            'built_up_area' => 'nullable|numeric',
            'carpet_area' => 'nullable|numeric',
            'age_of_property' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'furnish_type' => 'nullable|string',
            'amenities' => 'nullable|array',
            'covered_parking' => 'nullable|string',
            'open_parking' => 'nullable|string',
            'tenant_type' => 'nullable|string',
            'bachelor_preference' => 'nullable|string',
            'pet_friendly' => 'boolean',
            'available_from' => 'nullable|date',
            'maintenance_charges' => 'nullable|numeric',
            'maintenance_type' => 'nullable|string',
            'security_deposit' => 'nullable|string',
            'lock_in_period' => 'nullable|string',
            'brokerage_charge' => 'nullable|string',
            'brokerage_negotiable' => 'boolean',
            'parking_charges_type' => 'nullable|string',
            'painting_charges' => 'nullable|string',
            'floor_no' => 'nullable|integer',
            'total_floors' => 'nullable|integer',
            'facing' => 'nullable|string',
            'servant_room' => 'boolean',
            'rera_id' => 'nullable|string',
            'highlights' => 'nullable|array',
            'new_photos' => 'nullable|array',
        ]);

        $photos = [];
        $allFiles = $request->allFiles();
        $files = isset($allFiles['new_photos']) ? (is_array($allFiles['new_photos']) ? $allFiles['new_photos'] : [$allFiles['new_photos']]) : [];
        
        foreach ($files as $photo) {
            if (count($photos) >= 20) break;
            if ($photo instanceof \Illuminate\Http\UploadedFile) {
                try {
                    $photos[] = $this->imageService->processPropertyImage($photo);
                } catch (\Exception $e) {
                    Log::error('Store Photo processing failed: ' . $e->getMessage());
                }
            }
        }

        $validated['photos'] = $photos;
        $validated['image'] = $photos[0] ?? null;

        Auth::user()->properties()->create($validated);

        return redirect()->back()->with('message', 'Property created successfully.');
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'required|string',
            'type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'garage' => 'nullable|integer',
            'size' => 'nullable|integer',
            'is_featured' => 'boolean',
            'category' => 'required|string',
            'listing_type' => 'required|string',
            'bhk' => 'nullable|string',
            'society_name' => 'nullable|string',
            'built_up_area' => 'nullable|numeric',
            'carpet_area' => 'nullable|numeric',
            'age_of_property' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'furnish_type' => 'nullable|string',
            'amenities' => 'nullable|array',
            'covered_parking' => 'nullable|string',
            'open_parking' => 'nullable|string',
            'tenant_type' => 'nullable|string',
            'bachelor_preference' => 'nullable|string',
            'pet_friendly' => 'boolean',
            'available_from' => 'nullable|date',
            'maintenance_charges' => 'nullable|numeric',
            'maintenance_type' => 'nullable|string',
            'security_deposit' => 'nullable|string',
            'lock_in_period' => 'nullable|string',
            'brokerage_charge' => 'nullable|string',
            'brokerage_negotiable' => 'boolean',
            'parking_charges_type' => 'nullable|string',
            'painting_charges' => 'nullable|string',
            'floor_no' => 'nullable|integer',
            'total_floors' => 'nullable|integer',
            'facing' => 'nullable|string',
            'servant_room' => 'boolean',
            'rera_id' => 'nullable|string',
            'highlights' => 'nullable|array',
            'existing_photos' => 'nullable|array',
            'new_photos' => 'nullable|array',
        ]);

        $photos = $request->input('existing_photos', []);
        $allFiles = $request->allFiles();
        $files = isset($allFiles['new_photos']) ? (is_array($allFiles['new_photos']) ? $allFiles['new_photos'] : [$allFiles['new_photos']]) : [];
        
        foreach ($files as $photo) {
            if (count($photos) >= 20) break;
            if ($photo instanceof \Illuminate\Http\UploadedFile) {
                try {
                    $photos[] = $this->imageService->processPropertyImage($photo);
                } catch (\Exception $e) {
                    Log::error('Update Photo processing failed: ' . $e->getMessage());
                }
            }
        }

        $validated['photos'] = $photos;
        $validated['image'] = $photos[0] ?? (count($photos) > 0 ? $photos[0] : null);

        $property->update($validated);

        return redirect()->back()->with('message', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->back()->with('message', 'Property deleted successfully.');
    }
}
