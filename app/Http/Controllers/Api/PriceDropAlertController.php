<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Repositories\PriceDropAlertRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceDropAlertController extends Controller
{
    public function __construct(
        protected PriceDropAlertRepository $priceDropAlertRepository
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:64',
            'name' => 'nullable|string|max:255',
        ]);

        Property::query()->findOrFail($validated['property_id']);

        $this->priceDropAlertRepository->upsertAlert($validated);

        return response()->json([
            'success' => true,
            'message' => 'You will be notified if the price drops on this listing.',
        ]);
    }
}
