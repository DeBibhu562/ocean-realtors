<?php

namespace App\Repositories;

use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PropertyRepository
{
    /** @var array<string, list<string>> */
    protected array $typePatterns = [
        'apartment' => ['apartment', 'flat'],
        'house' => ['house', 'independent', 'floor', 'bungalow'],
        'villa' => ['villa'],
        'office' => ['office', 'commercial', 'shop', 'retail'],
        'commercial' => ['commercial', 'office', 'shop', 'retail'],
        'plot' => ['plot', 'land'],
        'pg' => ['pg', 'paying guest', 'co-living', 'coliving'],
    ];

    public function paginatedForListing(Request $request): LengthAwarePaginator
    {
        $query = Property::query()->with('user');

        $this->applyFilters($query, $request);

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $perPage = min((int) $request->get('per_page', 12), 48);

        $paginator = $query->paginate($perPage);
        $paginator->getCollection()->transform(fn (Property $p) => $this->toCard($p));

        return $paginator;
    }

    public function similar(Property $property, int $limit = 6): Collection
    {
        $list = Property::query()
            ->with('user')
            ->where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->latest()
            ->limit($limit)
            ->get();

        if ($list->count() < $limit) {
            $needed = $limit - $list->count();
            $more = Property::query()
                ->with('user')
                ->where('id', '!=', $property->id)
                ->whereNotIn('id', $list->pluck('id'))
                ->latest()
                ->limit($needed)
                ->get();
            $list = $list->concat($more);
        }

        return $list->map(fn (Property $p) => $this->toCard($p));
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        if ($keyword = trim((string) $request->get('keyword', ''))) {
            $kw = '%'.addcslashes($keyword, '%_\\').'%';
            $query->where(function (Builder $q) use ($kw) {
                $q->where('title', 'like', $kw)
                    ->orWhere('address', 'like', $kw)
                    ->orWhere('city', 'like', $kw)
                    ->orWhere('society_name', 'like', $kw)
                    ->orWhere('description', 'like', $kw);
            });
        }

        if ($city = trim((string) $request->get('city', ''))) {
            $cityKw = '%'.addcslashes($city, '%_\\').'%';
            $query->whereRaw('LOWER(city) LIKE LOWER(?)', [$cityKw]);
        }

        $status = $request->get('status', 'all');
        if ($status === 'rent') {
            $query->where(function (Builder $q) {
                $q->whereRaw('LOWER(listing_type) = ?', ['rent'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%rent%']);
            });
        } elseif ($status === 'sale') {
            $query->where(function (Builder $q) {
                $q->whereRaw('LOWER(listing_type) IN (?, ?)', ['sell', 'sale'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%sale%'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%sell%']);
            });
        }

        $types = $this->normalizeArrayInput($request->get('type'));
        if ($types !== []) {
            $query->where(function (Builder $q) use ($types) {
                $first = true;
                foreach ($types as $type) {
                    $patterns = $this->typePatterns[strtolower($type)] ?? [strtolower($type)];
                    foreach ($patterns as $pattern) {
                        $like = '%'.addcslashes($pattern, '%_\\').'%';
                        if ($first) {
                            $q->whereRaw('LOWER(`type`) LIKE ?', [$like]);
                            $first = false;
                        } else {
                            $q->orWhereRaw('LOWER(`type`) LIKE ?', [$like]);
                        }
                    }
                }
            });
        }

        if ($request->filled('min_price') && (float) $request->get('min_price') > 0) {
            $query->where('price', '>=', (float) $request->get('min_price'));
        }

        if ($request->filled('max_price') && (float) $request->get('max_price') > 0) {
            $query->where('price', '<=', (float) $request->get('max_price'));
        }

        if ($request->filled('beds')) {
            $beds = (int) $request->get('beds');
            $query->where(function (Builder $q) use ($beds) {
                $q->where('bedrooms', '>=', $beds)
                    ->orWhere('bhk', 'like', $beds.'%')
                    ->orWhere('bhk', 'like', $beds.' BHK%');
            });
        }

        if ($request->filled('baths')) {
            $query->where('bathrooms', '>=', (int) $request->get('baths'));
        }

        if ($request->filled('min_area')) {
            $min = (float) $request->get('min_area');
            $query->whereRaw('COALESCE(built_up_area, size, 0) >= ?', [$min]);
        }

        if ($request->filled('max_area')) {
            $max = (float) $request->get('max_area');
            $query->whereRaw('COALESCE(built_up_area, size, 0) <= ?', [$max]);
        }

        $amenities = $this->normalizeArrayInput($request->get('amenities'));
        foreach ($amenities as $amenity) {
            $query->where(function (Builder $q) use ($amenity) {
                $q->whereJsonContains('amenities', $amenity)
                    ->orWhereRaw('LOWER(CAST(amenities AS CHAR)) LIKE ?', ['%'.strtolower($amenity).'%']);
            });
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }
    }

    /**
     * @return list<string>
     */
    protected function normalizeArrayInput(mixed $input): array
    {
        if ($input === null || $input === '') {
            return [];
        }

        if (is_string($input)) {
            return array_values(array_filter(array_map('trim', explode(',', $input))));
        }

        if (! is_array($input)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($value) => is_string($value) ? trim($value) : (string) $value,
            $input
        )));
    }

    public function toCard(Property $property): array
    {
        $user = $property->user;
        $area = $property->built_up_area ?? $property->size ?? 0;
        $statusSlug = $this->statusSlug($property);

        return [
            'id' => $property->id,
            'title' => $property->title,
            'address' => $property->address,
            'city' => $property->city,
            'price' => (float) $property->price,
            'type' => $property->type,
            'status' => $statusSlug,
            'beds' => (int) $property->bedrooms,
            'baths' => (int) $property->bathrooms,
            'area' => (float) $area,
            'image' => $property->image,
            'isFeatured' => (bool) $property->is_featured,
            'agent' => [
                'name' => $user?->name,
                'phone' => $user?->phone,
                'avatar' => $user?->avatar,
            ],
        ];
    }

    public function suggestLocations(?string $query = null): Collection
    {
        $results = collect();

        if (! $query) {
            return $results;
        }

        $kw = '%'.addcslashes($query, '%_\\').'%';

        $cities = Property::query()
            ->select('city')
            ->whereRaw('LOWER(city) LIKE LOWER(?)', [$kw])
            ->distinct()
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'name' => $p->city,
                'type' => 'CITY',
                'subtitle' => strtoupper((string) $p->city),
            ]);
        $results = $results->concat($cities);

        $societies = Property::query()
            ->select('society_name', 'city')
            ->where('society_name', 'like', $kw)
            ->whereNotNull('society_name')
            ->distinct()
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'name' => $p->society_name,
                'type' => 'PROJECT',
                'subtitle' => strtoupper((string) $p->city),
            ]);
        $results = $results->concat($societies);

        $localities = Property::query()
            ->select('address', 'city')
            ->where('address', 'like', $kw)
            ->distinct()
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'name' => $p->address,
                'type' => 'LOCALITY',
                'subtitle' => strtoupper((string) $p->city),
            ]);
        $results = $results->concat($localities);

        return $results->unique('name')->values();
    }

    protected function statusSlug(Property $property): string
    {
        $lt = strtolower((string) $property->listing_type);
        if ($lt === 'rent' || str_contains(strtolower((string) $property->status), 'rent')) {
            return 'rent';
        }

        return 'sale';
    }
}
