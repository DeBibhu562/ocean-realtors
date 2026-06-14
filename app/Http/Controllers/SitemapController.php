<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\BlogPost;
use App\Models\Property;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        // Computing the full sitemap cache key includes DB counts/max timestamps.
        // Cache the cache-key itself to avoid running those queries on every request.
        $cacheKey = Cache::remember('sitemap.cacheKey', 3600, function () {
            return $this->cacheKey();
        });

        $xml = Cache::remember($cacheKey, 3600, function () {
            return $this->buildSitemap()->render();
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public static function clearCache(): void
    {
        $maybeKey = Cache::get('sitemap.cacheKey');
        Cache::forget('sitemap.cacheKey');
        Cache::forget('sitemap.xml'); // legacy key (kept for safety)
        if (is_string($maybeKey) && $maybeKey !== '') {
            Cache::forget($maybeKey);
        }
    }

    protected function cacheKey(): string
    {
        return 'sitemap.xml.'.$this->contentFingerprint();
    }

    protected function contentFingerprint(): string
    {
        $seoCount = count(config('seo_landing_pages.pages', []));
        $staticCount = count(config('seo.static_pages', []));

        $propertyQuery = Property::query()->whereNotNull('slug');
        if (Schema::hasColumn('properties', 'listing_status')) {
            $propertyQuery->where('listing_status', 'Active');
        }
        $propertyCount = $propertyQuery->count();
        $propertyStamp = (string) ($propertyQuery->max('updated_at') ?? '');

        $blogQuery = BlogPost::query()->published();
        $blogCount = $blogQuery->count();
        $blogStamp = (string) ($blogQuery->max('updated_at') ?? '');

        $agentQuery = Agent::query()->active();
        $agentCount = $agentQuery->count();
        $agentStamp = (string) ($agentQuery->max('updated_at') ?? '');

        return substr(md5(implode('|', [
            $seoCount,
            $staticCount,
            $propertyCount,
            $propertyStamp,
            $blogCount,
            $blogStamp,
            $agentCount,
            $agentStamp,
        ])), 0, 12);
    }

    protected function buildSitemap(): Sitemap
    {
        $base = rtrim((string) config('app.url'), '/');
        $sitemap = Sitemap::create();

        foreach (config('seo.static_pages', []) as $page) {
            $url = Url::create($base.$page['path'])
                ->setPriority($page['priority'] ?? 0.5);

            if (! empty($page['changefreq'])) {
                $url->setChangeFrequency($page['changefreq']);
            }

            $sitemap->add($url);
        }

        foreach (SeoLandingController::sitemapEntries() as $page) {
            $url = Url::create($base.$page['path'])
                ->setPriority($page['priority'] ?? 0.85)
                ->setChangeFrequency($page['changefreq'] ?? Url::CHANGE_FREQUENCY_WEEKLY);

            $sitemap->add($url);
        }

        BlogPost::query()
            ->published()
            ->when(
                Schema::hasColumn('blog_posts', 'noindex'),
                fn ($query) => $query->where(fn ($q) => $q->where('noindex', false)->orWhereNull('noindex'))
            )
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(100, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $sitemap->add(
                        Url::create($post->publicUrl())
                            ->setLastModificationDate($post->updated_at)
                            ->setPriority(0.7)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    );
                }
            });

        $propertyQuery = Property::query()
            ->whereNotNull('slug')
            ->select(['slug', 'updated_at']);

        if (Schema::hasColumn('properties', 'listing_status')) {
            $propertyQuery->where('listing_status', 'Active');
        }

        $propertyQuery
            ->orderByDesc('updated_at')
            ->chunk(100, function ($properties) use ($sitemap, $base) {
                foreach ($properties as $property) {
                    $sitemap->add(
                        Url::create("{$base}/properties/{$property->slug}")
                            ->setLastModificationDate($property->updated_at)
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    );
                }
            });

        Agent::query()
            ->active()
            ->select(['slug', 'updated_at'])
            ->orderBy('name')
            ->chunk(100, function ($agents) use ($sitemap, $base) {
                foreach ($agents as $agent) {
                    $sitemap->add(
                        Url::create("{$base}/agents/{$agent->slug}")
                            ->setLastModificationDate($agent->updated_at)
                            ->setPriority(0.6)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    );
                }
            });

        return $sitemap;
    }
}
