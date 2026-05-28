<?php

namespace App\Http\Controllers;

class RobotsController extends Controller
{
    public function __invoke(): \Illuminate\Http\Response
    {
        $base = rtrim((string) config('app.url'), '/');
        $disallow = config('seo.robots_disallow', []);

        $lines = ['User-agent: *', 'Allow: /'];

        foreach ($disallow as $path) {
            $lines[] = 'Disallow: '.$path;
        }

        $lines[] = '';
        $lines[] = 'Sitemap: '.$base.'/sitemap.xml';

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
