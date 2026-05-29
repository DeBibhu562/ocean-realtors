<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicBlogController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->string('q')->trim()->toString();

        $baseQuery = BlogPost::query()
            ->published()
            ->with('blogWriter')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            });

        $featured = (clone $baseQuery)->latest('published_at')->first();

        $postsQuery = clone $baseQuery;
        if ($featured) {
            $postsQuery->where('id', '!=', $featured->id);
        }

        $posts = $postsQuery
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (BlogPost $post) => $post->toListArray());

        return Inertia::render('Blog/Index', [
            'featured' => $featured?->toListArray(),
            'posts' => $posts,
            'filters' => ['q' => $search],
        ]);
    }

    public function show(BlogPost $post): Response
    {
        $canPreview = auth()->check() && auth()->user()?->is_admin === true;

        abort_unless($post->isPublished() || $canPreview, 404);

        $post->load('blogWriter');

        $related = BlogPost::query()
            ->published()
            ->with('blogWriter')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get()
            ->map(fn (BlogPost $p) => $p->toListArray());

        $reviewPayload = $this->reviewService->getPublicForTarget(Review::TARGET_BLOG_POST, $post->id, 10);

        return Inertia::render('Blog/Show', [
            'post' => $post->toPublicArray(),
            'related' => $related,
            'reviews' => $reviewPayload['reviews'],
            'review_stats' => $reviewPayload['stats'],
        ]);
    }
}
