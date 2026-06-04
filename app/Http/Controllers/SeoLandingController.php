<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeoLandingController extends Controller
{
    public function show(string $seoSlug): Response
    {
        $page = config("seo_landing_pages.pages.{$seoSlug}");

        if (! is_array($page)) {
            throw new NotFoundHttpException;
        }

        return Inertia::render('SeoLanding/Show', [
            'page' => array_merge($page, [
                'slug' => $seoSlug,
                'path' => '/'.$seoSlug,
            ]),
        ]);
    }

    /**
     * @return list<array{path: string, priority: float, changefreq: string, title: string, description: string}>
     */
    public static function sitemapEntries(): array
    {
        $entries = [];

        foreach (config('seo_landing_pages.pages', []) as $slug => $page) {
            if (! is_array($page)) {
                continue;
            }

            $entries[] = [
                'path' => '/'.$slug,
                'priority' => 0.85,
                'changefreq' => 'weekly',
                'title' => $page['meta_title'] ?? $page['title'] ?? $slug,
                'description' => $page['meta_description'] ?? '',
            ];
        }

        return $entries;
    }
}
