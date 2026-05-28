<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Geocoding\GeocoderInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class GeocodeController extends Controller
{
    public function reverse(Request $request, GeocoderInterface $geocoder): JsonResponse
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        return $this->respond(fn () => $geocoder->reverseGeocode(
            (float) $validated['lat'],
            (float) $validated['lng'],
        ));
    }

    public function search(Request $request, GeocoderInterface $geocoder): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|min:3|max:200',
            'city' => 'nullable|string|max:120',
        ]);

        return $this->respond(fn () => [
            'suggestions' => $geocoder->searchPlaces($validated['q'], $validated['city'] ?? null),
        ]);
    }

    public function place(Request $request, GeocoderInterface $geocoder): JsonResponse
    {
        $validated = $request->validate([
            'place_id' => 'required|string|max:255',
        ]);

        return $this->respond(fn () => $geocoder->placeDetails($validated['place_id']));
    }

    /**
     * @param  callable(): array<string, mixed>  $action
     */
    protected function respond(callable $action): JsonResponse
    {
        try {
            return response()->json($action());
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }
}
