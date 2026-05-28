<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    public function index(Request $request): Response
    {
        $status = $request->string('status')->toString();
        $targetType = $request->string('target_type')->toString();

        $reviews = Review::query()
            ->with(['property:id,title', 'blogPost:id,title,slug'])
            ->when(in_array($status, [Review::STATUS_PENDING, Review::STATUS_APPROVED, Review::STATUS_REJECTED], true), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when(in_array($targetType, Review::validTargetTypes(), true), function ($q) use ($targetType) {
                $q->where('target_type', $targetType);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString()
            ->through(fn (Review $review) => $review->toAdminArray());

        return Inertia::render('Admin/Reviews/Index', [
            'reviews' => $reviews,
            'filters' => [
                'status' => $status ?: null,
                'target_type' => $targetType ?: null,
            ],
            'counts' => [
                'pending' => Review::pending()->count(),
                'approved' => Review::approved()->count(),
                'rejected' => Review::rejected()->count(),
            ],
        ]);
    }

    public function approve(Review $review): RedirectResponse
    {
        $this->reviewService->approve($review, request()->user());

        return back()->with('success', 'Review approved.');
    }

    public function reject(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'rejected_reason' => 'nullable|string|max:500',
        ]);

        $this->reviewService->reject($review, $request->user(), $validated['rejected_reason'] ?? null);

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
