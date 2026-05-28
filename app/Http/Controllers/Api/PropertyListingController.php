<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyListingController extends Controller
{
    public function __construct(
        protected PropertyRepository $propertyRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->propertyRepository->paginatedForListing($request);

        return response()->json($paginator);
    }

    public function featured(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $limit = min(max((int) $request->query('limit', 6), 1), 12);

        $items = $this->propertyRepository->featuredForHomepage(
            is_string($type) && $type !== '' ? $type : null,
            $limit
        );

        return response()->json(['data' => $items]);
    }

    public function similar(Property $property): JsonResponse
    {
        $items = $this->propertyRepository->similar($property, 6);

        return response()->json([
            'data' => $items,
        ]);
    }
    public function locations(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $locations = $this->propertyRepository->suggestLocations($query);

        return response()->json($locations);
    }

    public function cities(): JsonResponse
    {
        return response()->json($this->propertyRepository->listedCities());
    }

    public function areas(Request $request): JsonResponse
    {
        $city = $request->string('city')->trim()->toString();
        if ($city === '') {
            return response()->json([]);
        }

        return response()->json($this->propertyRepository->listedAreas($city));
    }

    public function localities(Request $request): JsonResponse
    {
        $city = $request->string('city')->trim()->toString();
        if ($city === '') {
            return response()->json([]);
        }

        $area = $request->string('area')->trim()->toString();

        return response()->json(
            $this->propertyRepository->listedLocalities($city, $area !== '' ? $area : null)
        );
    }
}
