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
}
