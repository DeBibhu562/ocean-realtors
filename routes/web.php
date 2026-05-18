<?php

use App\Http\Controllers\Api\LeadController as ApiLeadController;
use App\Http\Controllers\Api\PriceDropAlertController;
use App\Http\Controllers\Api\PropertyListingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SystemSettingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/contact', function () {
    return Inertia::render('Contact');
});

Route::get('/agents', function () {
    return Inertia::render('Agents');
});

Route::get('/blog', function () {
    return Inertia::render('Blog');
});

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
    Route::get('/properties/{property}/similar', [PropertyListingController::class, 'similar']);
    Route::post('/leads', [ApiLeadController::class, 'store']);
    Route::post('/alerts', [PriceDropAlertController::class, 'store']);
    Route::get('/locations', [PropertyListingController::class, 'locations']);
});

// Dashboard & Management
Route::get('/dashboard', [PropertyController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/leads', [App\Http\Controllers\LeadController::class, 'index'])->name('leads.index');
    Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/watermark', [SystemSettingController::class, 'updateWatermark'])->name('settings.watermark');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);

require __DIR__.'/auth.php';
