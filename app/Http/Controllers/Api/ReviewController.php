<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Property;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target_type' => ['required', Rule::in(Review::validTargetTypes())],
            'target_id' => 'nullable|integer|min:1',
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $targetType = $validated['target_type'];
        $targetId = isset($validated['target_id']) ? (int) $validated['target_id'] : null;

        if ($targetType === Review::TARGET_SITE) {
            $targetId = null;
        } elseif ($targetId === null) {
            return response()->json(['message' => 'target_id is required for this review type.'], 422);
        }

        $this->assertTargetExists($targetType, $targetId);

        $limit = (int) ($validated['limit'] ?? 10);
        $offset = (int) ($validated['offset'] ?? 0);

        $payload = $this->reviewService->getPublicForTarget($targetType, $targetId, $limit, $offset);

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->filled('website')) {
            return response()->json(['message' => 'Unable to submit review.'], 422);
        }

        $validated = $request->validate([
            'target_type' => ['required', Rule::in(Review::validTargetTypes())],
            'target_id' => 'nullable|integer|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string|min:10|max:2000',
            'website' => 'nullable|string|max:0',
        ]);

        $targetType = $validated['target_type'];
        $targetId = isset($validated['target_id']) ? (int) $validated['target_id'] : null;

        if ($targetType === Review::TARGET_SITE) {
            $targetId = null;
        } elseif ($targetId === null) {
            return response()->json(['message' => 'target_id is required for this review type.'], 422);
        }

        $this->assertTargetExists($targetType, $targetId);

        if ($this->reviewService->hasRecentDuplicate($validated['email'], $targetType, $targetId)) {
            return response()->json([
                'message' => 'You have already submitted a review recently. Please try again later.',
            ], 422);
        }

        $this->reviewService->createPending([
            'target_type' => $targetType,
            'target_id' => $targetId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
        ], $request->ip());

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted and will appear after approval.',
        ]);
    }

    protected function assertTargetExists(string $targetType, ?int $targetId): void
    {
        if ($targetType === Review::TARGET_SITE) {
            return;
        }

        $exists = match ($targetType) {
            Review::TARGET_PROPERTY => Property::query()->whereKey($targetId)->exists(),
            Review::TARGET_BLOG_POST => BlogPost::query()->published()->whereKey($targetId)->exists(),
            default => false,
        };

        if (! $exists) {
            abort(404, 'Review target not found.');
        }
    }
}
