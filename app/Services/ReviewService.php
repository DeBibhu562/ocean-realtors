<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ReviewService
{
    /**
     * @return array{reviews: list<array<string, mixed>>, stats: array{total_count: int, average_rating: float}}
     */
    public function getPublicForTarget(string $targetType, ?int $targetId, int $limit = 10, int $offset = 0): array
    {
        $query = Review::query()
            ->approved()
            ->forTarget($targetType, $targetId);

        $total = (clone $query)->count();
        $average = $total > 0 ? (float) (clone $query)->avg('rating') : 0.0;

        $reviews = (clone $query)
            ->latest('approved_at')
            ->offset(max(0, $offset))
            ->limit(min(max($limit, 1), 50))
            ->get()
            ->map(fn (Review $review) => $review->toPublicArray())
            ->values()
            ->all();

        return [
            'reviews' => $reviews,
            'stats' => [
                'total_count' => $total,
                'average_rating' => round($average, 1),
            ],
        ];
    }

    public function hasRecentDuplicate(string $email, string $targetType, ?int $targetId): bool
    {
        return Review::query()
            ->where('email', $email)
            ->forTarget($targetType, $targetId)
            ->where('created_at', '>=', now()->subDay())
            ->exists();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createPending(array $data, ?string $ip = null): Review
    {
        return Review::create([
            'target_type' => $data['target_type'],
            'target_id' => $data['target_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'],
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'],
            'status' => Review::STATUS_PENDING,
            'ip_address' => $ip,
        ]);
    }

    public function approve(Review $review, User $user): Review
    {
        $review->update([
            'status' => Review::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $user->id,
            'rejected_reason' => null,
        ]);

        $this->bumpVersion();

        return $review->fresh();
    }

    public function reject(Review $review, User $user, ?string $reason = null): Review
    {
        $review->update([
            'status' => Review::STATUS_REJECTED,
            'approved_at' => null,
            'approved_by' => $user->id,
            'rejected_reason' => $reason,
        ]);

        $this->bumpVersion();

        return $review->fresh();
    }

    protected function bumpVersion(): void
    {
        try {
            Cache::increment('reviews.public.version');
        } catch (\Throwable) {
            //
        }
    }
}
