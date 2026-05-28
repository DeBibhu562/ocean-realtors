<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBlogWriterRequest;
use App\Models\BlogWriter;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
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

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->processBlogWriterPhoto($request->file('photo'));
        }

        $blogWriter->update($data);

        return redirect()
            ->route('admin.blog-writers.index')
            ->with('message', 'Writer profile updated successfully.');
    }
}
