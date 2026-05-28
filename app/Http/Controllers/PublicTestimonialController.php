<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\ReviewService;
use Inertia\Inertia;
use Inertia\Response;

class PublicTestimonialController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    public function index(): Response
    {
        $payload = $this->reviewService->getPublicForTarget(Review::TARGET_SITE, null, 50);

        return Inertia::render('Testimonials', [
            'reviews' => $payload['reviews'],
            'review_stats' => $payload['stats'],
        ]);
    }
}
