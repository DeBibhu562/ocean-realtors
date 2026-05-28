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
            ->with('user')
            ->latest()
            ->paginate(12)
            ->withQueryString();

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

        Auth::user()->properties()->create($payload);

        return $this->redirectAfterMutation('Property created successfully.');
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $this->authorizeProperty($property);

        $validated = $request->validate($this->listingRules(strict: true));
        $payload = $this->buildPayload($request, $validated, $property);

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

        return PropertyPayloadNormalizer::forStorage($validated);
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
     * @return array<string, mixed>
     */
    protected function listingRules(bool $strict): array
    {
        $required = $strict ? 'required' : 'sometimes';

        $rules = [
            'title' => [$required, 'string', 'max:255'],
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
            'available_from' => ['nullable', 'date'],
            'maintenance_charges' => ['nullable', 'numeric'],
            'maintenance_type' => ['nullable', 'string'],
            'security_deposit' => ['nullable', 'string'],
            'lock_in_period' => ['nullable', 'string'],
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
