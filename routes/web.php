<?php

use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Models\Property;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BlogWriterController as AdminBlogWriterController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Api\LeadController as ApiLeadController;
use App\Http\Controllers\Api\PriceDropAlertController;
use App\Http\Controllers\Api\PropertyListingController;
use App\Http\Controllers\Dashboard\GeocodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PublicAgentController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\SystemSettingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'welcome']);

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/contact', function () {
    return Inertia::render('Contact');
});

Route::get('/agents', [PublicAgentController::class, 'index'])->name('agents.index');
Route::get('/agents/{agent}', [PublicAgentController::class, 'show'])->name('agents.show');

Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [PublicBlogController::class, 'show'])->name('blog.show');

Route::get('/mission', function () { return Inertia::render('Mission'); });
Route::get('/why-choose-us', function () { return Inertia::render('WhyChooseUs'); });
Route::get('/testimonials', function () { return Inertia::render('Testimonials'); });
Route::get('/privacy-policy', function () { return Inertia::render('PrivacyPolicy'); });
Route::get('/terms', function () { return Inertia::render('Terms'); });
Route::get('/proposal', function () { return Inertia::render('BusinessProposal'); });
Route::get('/ads', function () { return Inertia::render('Advertisements'); });
Route::get('/careers', function () { return Inertia::render('Careers'); });

// Dynamic Property Routes
Route::get('/properties', [PropertyController::class, 'publicIndex'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

Route::prefix('api')->middleware('web')->group(function () {
    Route::get('/properties', [PropertyListingController::class, 'index']);
    Route::get('/properties/featured', [PropertyListingController::class, 'featured']);
    Route::get('/properties/{property}/similar', [PropertyListingController::class, 'similar']);
    Route::post('/leads', [ApiLeadController::class, 'store']);
    Route::post('/alerts', [PriceDropAlertController::class, 'store']);
    Route::get('/locations', [PropertyListingController::class, 'locations']);
    Route::get('/locations/cities', [PropertyListingController::class, 'cities']);
    Route::get('/locations/areas', [PropertyListingController::class, 'areas']);
    Route::get('/locations/localities', [PropertyListingController::class, 'localities']);
});

// Dashboard & Management
Route::get('/dashboard', [PropertyController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/leads', [App\Http\Controllers\LeadController::class, 'index'])->name('leads.index');
    Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/watermark', [SystemSettingController::class, 'updateWatermark'])->name('settings.watermark');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::patch('/properties/{property}/autosave', [PropertyController::class, 'autosave'])->name('properties.autosave');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');

    Route::middleware('throttle:60,1')->prefix('dashboard/api/geocode')->name('dashboard.geocode.')->group(function () {
        Route::post('/reverse', [GeocodeController::class, 'reverse'])->name('reverse');
        Route::post('/search', [GeocodeController::class, 'search'])->name('search');
        Route::post('/place', [GeocodeController::class, 'place'])->name('place');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/agents', [AdminAgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/create', [AdminAgentController::class, 'create'])->name('agents.create');
    Route::post('/agents', [AdminAgentController::class, 'store'])->name('agents.store');
    Route::get('/agents/{agent}/edit', [AdminAgentController::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{agent}', [AdminAgentController::class, 'update'])->name('agents.update');
    Route::patch('/agents/{agent}/toggle', [AdminAgentController::class, 'toggle'])->name('agents.toggle');
    Route::delete('/agents/{agent}', [AdminAgentController::class, 'destroy'])->name('agents.destroy');

    Route::get('/blog', [AdminBlogPostController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [AdminBlogPostController::class, 'create'])->name('blog.create');
    Route::post('/blog', [AdminBlogPostController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}/edit', [AdminBlogPostController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [AdminBlogPostController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [AdminBlogPostController::class, 'destroy'])->name('blog.destroy');
    Route::patch('/blog/{blog}/toggle', [AdminBlogPostController::class, 'togglePublish'])->name('blog.toggle');

    Route::get('/blog-writers', [AdminBlogWriterController::class, 'index'])->name('blog-writers.index');
    Route::get('/blog-writers/create', [AdminBlogWriterController::class, 'create'])->name('blog-writers.create');
    Route::post('/blog-writers', [AdminBlogWriterController::class, 'store'])->name('blog-writers.store');
    Route::get('/blog-writers/{blogWriter}/edit', [AdminBlogWriterController::class, 'edit'])->name('blog-writers.edit');
    Route::put('/blog-writers/{blogWriter}', [AdminBlogWriterController::class, 'update'])->name('blog-writers.update');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);

require __DIR__.'/auth.php';

// Legacy public URLs used slug at site root (e.g. /3-bhk-apartment-in-gurgaon-6).
// Must remain last so it does not shadow /login, /register, etc.
Route::get('/{propertySlug}', function (string $propertySlug) {
    $reserved = [
        'login', 'register', 'logout', 'dashboard', 'admin', 'api',
        'profile', 'leads', 'settings', 'forgot-password', 'reset-password',
        'verify-email', 'confirm-password',
    ];

    if (in_array($propertySlug, $reserved, true)) {
        abort(404);
    }

    $property = Property::query()->where('slug', $propertySlug)->first();

    if (! $property) {
        abort(404);
    }

    return redirect()->route('properties.show', $property, 301);
})->where('propertySlug', '[a-z0-9][a-z0-9-]*');
