<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Models\BlogWriter;
use App\Services\ImageService;
use App\Support\BlogContentSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BlogPostController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();

        $posts = BlogPost::query()
            ->with('blogWriter:id,name')
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (BlogPost $post) => $this->formatForAdminList($post));

        return Inertia::render('Admin/Blog/Index', [
            'posts' => $posts,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Blog/Form', [
            'post' => null,
            'writers' => $this->writersForForm(),
        ]);
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        DB::transaction(function () use ($request, &$data) {
            $data = $this->prepareData($data, $request);
            BlogPost::create(array_merge($data, [
                'user_id' => $request->user()->id,
            ]));
        });
        Cache::forget('sitemap.xml');

        return redirect()->route('admin.blog.index')->with('message', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog): Response
    {
        return Inertia::render('Admin/Blog/Form', [
            'post' => $this->formatForAdminForm($blog),
            'writers' => $this->writersForForm(),
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blog): RedirectResponse
    {
        $data = $request->validated();
        DB::transaction(function () use ($request, $blog, &$data) {
            $data = $this->prepareData($data, $request, $blog);
            $blog->update($data);
        });
        Cache::forget('sitemap.xml');

        return redirect()->route('admin.blog.index')->with('message', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $blog->delete();
        Cache::forget('sitemap.xml');

        return redirect()->route('admin.blog.index')->with('message', 'Blog post deleted.');
    }

    public function togglePublish(BlogPost $blog): RedirectResponse
    {
        if ($blog->status === BlogPost::STATUS_PUBLISHED) {
            $blog->update([
                'status' => BlogPost::STATUS_DRAFT,
            ]);
        } else {
            $blog->update([
                'status' => BlogPost::STATUS_PUBLISHED,
                'published_at' => $blog->published_at ?? now(),
            ]);
        }

        return redirect()->back()->with('message', 'Publish status updated.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function prepareData(array $validated, Request $request, ?BlogPost $existing = null): array
    {
        unset($validated['featured_image'], $validated['og_image']);

        $validated['content'] = BlogContentSanitizer::clean($validated['content']);
        $validated['noindex'] = (bool) ($validated['noindex'] ?? false);
        $validated['reading_time_minutes'] = BlogPost::estimateReadingTime($validated['content']);

        if (empty($validated['excerpt'])) {
            $validated['excerpt'] = \Illuminate\Support\Str::limit(strip_tags($validated['content']), 160);
        }

        if ($validated['status'] === BlogPost::STATUS_PUBLISHED) {
            $validated['published_at'] = $validated['published_at'] ?? $existing?->published_at ?? now();
        } elseif ($validated['status'] === BlogPost::STATUS_DRAFT) {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('featured_image')) {
            try {
                $validated['featured_image'] = $this->imageService->processBlogFeaturedImage($request->file('featured_image'));
            } catch (\Throwable $e) {
                Log::warning('Blog featured image processing failed.', ['error' => $e->getMessage()]);
                throw ValidationException::withMessages([
                    'featured_image' => 'Featured image processing failed. Please upload a different image.',
                ]);
            }
        }

        if ($request->hasFile('og_image')) {
            try {
                $validated['og_image'] = $this->imageService->processBlogOgImage($request->file('og_image'));
            } catch (\Throwable $e) {
                Log::warning('Blog OG image processing failed.', ['error' => $e->getMessage()]);
                throw ValidationException::withMessages([
                    'og_image' => 'OG image processing failed. Please upload a different image.',
                ]);
            }
        }

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    protected function formatForAdminList(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $post->status,
            'featured_image' => $post->featuredImageUrl(),
            'published_at' => $post->published_at?->toIso8601String(),
            'updated_at' => $post->updated_at?->toIso8601String(),
            'author' => $post->blogWriter?->name ?? '—',
            'is_published' => $post->isPublished(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function formatForAdminForm(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->getRawOriginal('excerpt') ?? '',
            'content' => $post->content,
            'featured_image' => $post->featuredImageUrl(),
            'og_image' => $post->ogImageUrl(),
            'status' => $post->status,
            'published_at' => $post->published_at?->format('Y-m-d\TH:i'),
            'meta_title' => $post->meta_title ?? '',
            'meta_description' => $post->meta_description ?? '',
            'meta_keywords' => $post->meta_keywords ?? '',
            'canonical_url' => $post->canonical_url ?? '',
            'noindex' => (bool) $post->noindex,
            'blog_writer_id' => $post->blog_writer_id,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function writersForForm(): array
    {
        return BlogWriter::query()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (BlogWriter $writer) => $writer->toPublicArray())
            ->all();
    }
}
