<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogWriterRequest;
use App\Http\Requests\UpdateBlogWriterRequest;
use App\Models\BlogWriter;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BlogWriterController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    public function index(): Response
    {
        $writers = BlogWriter::query()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (BlogWriter $writer) => $writer->toAdminArray());

        return Inertia::render('Admin/BlogWriters/Index', [
            'writers' => $writers,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/BlogWriters/Create');
    }

    public function store(StoreBlogWriterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photo']);

        DB::transaction(function () use ($request, &$data) {
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->processWriterPhotoOrFail($request->file('photo'));
            }

            BlogWriter::create($data);
        });

        return redirect()
            ->route('admin.blog-writers.index')
            ->with('message', 'Writer created successfully.');
    }

    public function edit(BlogWriter $blogWriter): Response
    {
        return Inertia::render('Admin/BlogWriters/Edit', [
            'writer' => $blogWriter->toAdminArray(),
        ]);
    }

    public function update(UpdateBlogWriterRequest $request, BlogWriter $blogWriter): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photo']);

        DB::transaction(function () use ($request, $blogWriter, &$data) {
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->processWriterPhotoOrFail($request->file('photo'));
            }

            $blogWriter->update($data);
        });

        return redirect()
            ->route('admin.blog-writers.index')
            ->with('message', 'Writer profile updated successfully.');
    }

    protected function processWriterPhotoOrFail($file): string
    {
        try {
            return $this->imageService->processBlogWriterPhoto($file);
        } catch (\Throwable $e) {
            Log::warning('Blog writer photo processing failed.', ['error' => $e->getMessage()]);
            throw ValidationException::withMessages([
                'photo' => 'Photo processing failed. Please upload a valid image and try again.',
            ]);
        }
    }
}
