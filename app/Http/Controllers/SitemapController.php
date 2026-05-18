<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create('/properties')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY))
            ->add(Url::create('/about')->setPriority(0.7))
            ->add(Url::create('/contact')->setPriority(0.7));

        Property::all()->each(function (Property $property) use ($sitemap) {
            $sitemap->add(
                Url::create("/properties/{$property->id}")
                    ->setLastModificationDate($property->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        return $sitemap->toResponse(request());
    }
}
