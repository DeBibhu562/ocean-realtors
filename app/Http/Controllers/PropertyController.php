<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Property;
use App\Models\Review;
use App\Models\SystemSetting;
use App\Services\ImageService;
use App\Support\AgentPresenter;
use App\Support\PropertyPayloadNormalizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    public function index(): Response
    {
        $properties = Property::query()
            ->with(['user', 'agent'])
            ->latest()
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Property $property) => $this->transformPropertyForDashboard($property));

        $statsQuery = Property::query();
        $propertyStats = [
            'total' => (clone $statsQuery)->count(),
            'active' => Schema::hasColumn('properties', 'listing_status')
                ? (clone $statsQuery)->where('listing_status', 'Active')->count()
                : (clone $statsQuery)->count(),
            'featured' => (clone $statsQuery)->where('is_featured', true)->count(),
        ];

        $agents = Agent::query()
            ->when(
                method_exists(Agent::class, 'scopeActive'),
                fn ($q) => $q->active()
            )
            ->orderBy('name')
            ->get(['id', 'name', 'city']);

        $pendingReviewsCount = Schema::hasTable('reviews')
            ? Review::query()->pending()->count()
            : 0;

        $watermark = SystemSetting::get('watermark_path');

        return Inertia::render('Dashboard', [
            'properties' => $properties,
            'propertyStats' => $propertyStats,
            'agents' => $agents,
            'pendingReviewsCount' => $pendingReviewsCount,
            'watermark' => $watermark ? '/storage/'.$watermark : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function transformPropertyForDashboard(Property $property): array
    {
        $photos = is_array($property->photos) ? array_values(array_filter($property->photos)) : [];
        $image = $property->image ?: ($photos[0] ?? null);

        return [
            'id' => $property->id,
            'title' => (string) $property->title,
            'slug' => $property->slug,
            'description' => (string) ($property->description ?? ''),
            'price' => (float) ($property->price ?? 0),
            'status' => (string) ($property->status ?? ''),
            'listing_type' => (string) ($property->listing_type ?? ''),
            'listing_status' => (string) ($property->listing_status ?? ''),
            'type' => (string) ($property->type ?? ''),
            'category' => (string) ($property->category ?? ''),
            'address' => (string) ($property->address ?? ''),
            'city' => (string) ($property->city ?? ''),
            'area' => (string) ($property->area ?? ''),
            'locality' => (string) ($property->locality ?? ''),
            'society_name' => (string) ($property->society_name ?? ''),
            'bhk' => (string) ($property->bhk ?? ''),
            'bedrooms' => $property->bedrooms !== null ? (int) $property->bedrooms : null,
            'bathrooms' => $property->bathrooms !== null ? (int) $property->bathrooms : null,
            'garage' => $property->garage !== null ? (int) $property->garage : null,
            'size' => $property->size !== null ? (int) $property->size : null,
            'built_up_area' => $property->built_up_area !== null ? (float) $property->built_up_area : null,
            'carpet_area' => $property->carpet_area !== null ? (float) $property->carpet_area : null,
            'age_of_property' => $property->age_of_property !== null ? (int) $property->age_of_property : null,
            'balconies' => $property->balconies !== null ? (int) $property->balconies : null,
            'furnish_type' => (string) ($property->furnish_type ?? ''),
            'covered_parking' => (string) ($property->covered_parking ?? ''),
            'open_parking' => (string) ($property->open_parking ?? ''),
            'tenant_type' => (string) ($property->tenant_type ?? ''),
            'bachelor_preference' => (string) ($property->bachelor_preference ?? ''),
            'pet_friendly' => (bool) ($property->pet_friendly ?? false),
            'price_negotiable' => (bool) ($property->price_negotiable ?? false),
            'pg_food_included' => (bool) ($property->pg_food_included ?? false),
            'available_from' => $property->available_from?->toDateString(),
            'maintenance_charges' => $property->maintenance_charges !== null ? (float) $property->maintenance_charges : null,
            'maintenance_type' => (string) ($property->maintenance_type ?? ''),
            'security_deposit' => (string) ($property->security_deposit ?? ''),
            'lock_in_period' => (string) ($property->lock_in_period ?? ''),
            'booking_amount' => $property->booking_amount !== null ? (float) $property->booking_amount : null,
            'food_charges' => $property->food_charges !== null ? (float) $property->food_charges : null,
            'brokerage_charge' => (string) ($property->brokerage_charge ?? ''),
            'brokerage_negotiable' => (bool) ($property->brokerage_negotiable ?? false),
            'parking_charges_type' => (string) ($property->parking_charges_type ?? ''),
            'painting_charges' => (string) ($property->painting_charges ?? ''),
            'floor_no' => $property->floor_no !== null ? (int) $property->floor_no : null,
            'total_floors' => $property->total_floors !== null ? (int) $property->total_floors : null,
            'facing' => (string) ($property->facing ?? ''),
            'servant_room' => (bool) ($property->servant_room ?? false),
            'rera_id' => (string) ($property->rera_id ?? ''),
            'amenities' => is_array($property->amenities) ? $property->amenities : [],
            'highlights' => is_array($property->highlights) ? $property->highlights : [],
            'image' => $image,
            'photos' => $photos,
            'is_featured' => (bool) ($property->is_featured ?? false),
            'latitude' => $property->latitude !== null ? (float) $property->latitude : null,
            'longitude' => $property->longitude !== null ? (float) $property->longitude : null,
            'agent_id' => $property->agent_id,
            'agent' => AgentPresenter::forProperty($property->agent),
            'created_at' => $property->created_at?->toIso8601String(),
            'updated_at' => $property->updated_at?->toIso8601String(),
        ];
    }

    public function publicIndex(): Response
    {
        return Inertia::render('Properties/Index');
    }

    public function show(Property $property): Response
    {
        $property->load(['user', 'agent']);

        if (Schema::hasColumn('properties', 'views_count')) {
            $property->increment('views_count');
            $property->refresh();
        }

        return Inertia::render('Properties/Show', [
            'property' => $this->transformPropertyForPage($property),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->listingRules(strict: true));
        $payload = $this->buildPayload($request, $validated);

        $property = Auth::user()->properties()->create($payload);
        $property->update($this->finalizeComputedSeoPayload($property, $payload));

        return $this->redirectAfterMutation('Property created successfully.');
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $this->authorizeProperty($property);

        $validated = $request->validate($this->listingRules(strict: true));
        $payload = $this->buildPayload($request, $validated, $property);
        $payload = $this->finalizeComputedSeoPayload($property, $payload);

        $property->update($payload);

        return $this->redirectAfterMutation('Property updated successfully.');
    }

    public function autosave(Request $request, Property $property): JsonResponse
    {
        $this->authorizeProperty($property);

        $validated = $request->validate($this->listingRules(strict: false));
        $payload = $this->buildPayload($request, $validated, $property, includePhotos: false);

        $property->update($payload);

        return response()->json(['message' => 'Saved']);
    }

    public function destroy(Property $property): RedirectResponse
    {
        $this->authorizeProperty($property);

        $property->delete();

        return $this->redirectAfterMutation('Property deleted successfully.');
    }

    protected function redirectAfterMutation(string $message): RedirectResponse
    {
        return to_route('dashboard')
            ->with('message', $message)
            ->setStatusCode(303);
    }

    protected function authorizeProperty(Property $property): void
    {
        if ((int) $property->user_id !== (int) Auth::id() && ! Auth::user()?->is_admin) {
            abort(403);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildPayload(
        Request $request,
        array $validated,
        ?Property $property = null,
        bool $includePhotos = true
    ): array {
        if ($includePhotos) {
            $validated = $this->mergePhotos($request, $validated, $property);
        } else {
            unset($validated['existing_photos'], $validated['new_photos']);
        }

        $payload = PropertyPayloadNormalizer::forStorage($validated);

        return PropertyPayloadNormalizer::applyComputedTitleAndSeo($payload, $property?->id);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function finalizeComputedSeoPayload(Property $property, array $payload): array
    {
        $computed = PropertyPayloadNormalizer::applyComputedTitleAndSeo($payload, $property->id);
        $computed['slug'] = Property::uniqueSlug((string) $computed['title'], $property->id);

        return $computed;
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function mergePhotos(Request $request, array $validated, ?Property $property = null): array
    {
        $photos = $request->input('existing_photos', $property?->photos ?? []);
        if (! is_array($photos)) {
            $photos = [];
        }
        $photos = $this->normalizeSubmittedPhotoPaths($photos);

        $allFiles = $request->allFiles();
        $files = isset($allFiles['new_photos'])
            ? (is_array($allFiles['new_photos']) ? $allFiles['new_photos'] : [$allFiles['new_photos']])
            : [];

        foreach ($files as $photo) {
            if (count($photos) >= 20) {
                break;
            }

            if ($photo instanceof \Illuminate\Http\UploadedFile) {
                try {
                    $photos[] = $this->imageService->processPropertyImage($photo);
                } catch (\Exception $e) {
                    Log::error('Photo processing failed: '.$e->getMessage());
                }
            }
        }

        $validated['photos'] = array_values($photos);
        $validated['image'] = $photos[0] ?? null;

        return $validated;
    }

    /**
     * Ensure user-submitted local images pass through the centralized processing pipeline.
     *
     * @param  array<int, mixed>  $photos
     * @return array<int, string>
     */
    protected function normalizeSubmittedPhotoPaths(array $photos): array
    {
        $normalized = [];

        foreach ($photos as $photoPath) {
            if (! is_string($photoPath) || trim($photoPath) === '') {
                continue;
            }

            $path = trim($photoPath);
            $processedPath = $path;

            $isHttp = str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
            $alreadyNormalized = (bool) preg_match('/^(?:\/storage\/)?properties\/.+\.webp$/i', $path);
            if (! $isHttp && ! $alreadyNormalized) {
                try {
                    $reprocessed = $this->imageService->processPropertyImageFromPublicPath($path);
                    if ($reprocessed) {
                        $processedPath = $reprocessed;
                    }
                } catch (\Throwable $e) {
                    Log::warning('Existing photo path could not be reprocessed.', [
                        'path' => $path,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $normalized[] = $processedPath;
        }

        return array_values(array_unique($normalized));
    }

    /**
     * @return array<string, mixed>
     */
    protected function listingRules(bool $strict): array
    {
        $required = $strict ? 'required' : 'sometimes';

        $rules = [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => [$required, 'numeric', 'min:0'],
            'status' => [$required, 'string'],
            'type' => [$required, 'string'],
            'address' => [$required, 'string'],
            'city' => [$required, 'string'],
            'image' => ['nullable', 'string'],
            'bedrooms' => ['nullable', 'integer'],
            'bathrooms' => ['nullable', 'integer'],
            'garage' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer'],
            'is_featured' => ['sometimes', 'boolean'],
            'category' => [$required, 'string'],
            'listing_type' => [$required, 'string'],
            'listing_status' => ['nullable', 'string', 'max:32'],
            'bhk' => ['nullable', 'string'],
            'society_name' => ['nullable', 'string'],
            'built_up_area' => ['nullable', 'numeric'],
            'carpet_area' => ['nullable', 'numeric'],
            'age_of_property' => ['nullable', 'integer'],
            'balconies' => ['nullable', 'integer'],
            'furnish_type' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'covered_parking' => ['nullable', 'string'],
            'open_parking' => ['nullable', 'string'],
            'tenant_type' => ['nullable', 'string'],
            'bachelor_preference' => ['nullable', 'string'],
            'pet_friendly' => ['sometimes', 'boolean'],
            'price_negotiable' => ['sometimes', 'boolean'],
            'pg_food_included' => ['sometimes', 'boolean'],
            'available_from' => ['nullable', 'date'],
            'maintenance_charges' => ['nullable', 'numeric'],
            'maintenance_type' => ['nullable', 'string'],
            'security_deposit' => ['nullable', 'string'],
            'lock_in_period' => ['nullable', 'string'],
            'booking_amount' => ['nullable', 'numeric', 'min:0'],
            'food_charges' => ['nullable', 'numeric', 'min:0'],
            'brokerage_charge' => ['nullable', 'string'],
            'brokerage_negotiable' => ['sometimes', 'boolean'],
            'parking_charges_type' => ['nullable', 'string'],
            'painting_charges' => ['nullable', 'string'],
            'floor_no' => ['nullable', 'integer'],
            'total_floors' => ['nullable', 'integer'],
            'facing' => ['nullable', 'string'],
            'servant_room' => ['sometimes', 'boolean'],
            'rera_id' => ['nullable', 'string'],
            'highlights' => ['nullable', 'array'],
            'existing_photos' => ['nullable', 'array'],
            'new_photos' => ['nullable', 'array'],
            'new_photos.*' => ['nullable', 'image', 'max:10240'],
            'save_as_draft' => ['sometimes', 'boolean'],
            'title_locked' => ['sometimes', 'boolean'],
            'area' => ['nullable', 'string'],
            'locality' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];

        if (! $strict) {
            foreach (['title', 'price', 'status', 'type', 'address', 'city', 'category', 'listing_type'] as $key) {
                if (isset($rules[$key][0]) && $rules[$key][0] === 'required') {
                    $rules[$key][0] = 'sometimes';
                }
            }
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    protected function transformPropertyForPage(Property $property): array
    {
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
            'agent' => AgentPresenter::forProperty($property->agent),
        ];
    }
}
