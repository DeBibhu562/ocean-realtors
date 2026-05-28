<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\BlogPost;
use App\Models\Property;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            return $this->buildSitemap()->render();
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
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

        BlogPost::query()
            ->published()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(100, function ($posts) use ($sitemap, $base) {
                foreach ($posts as $post) {
                    $sitemap->add(
                        Url::create("{$base}/blog/{$post->slug}")
                            ->setLastModificationDate($post->updated_at)
                            ->setPriority(0.7)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    );
                }
            });

        Property::query()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(100, function ($properties) use ($sitemap, $base) {
                foreach ($properties as $property) {
                    $sitemap->add(
                        Url::create("{$base}/{$property->slug}")
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
