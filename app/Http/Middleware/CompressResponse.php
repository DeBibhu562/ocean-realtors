<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! str_contains($request->header('Accept-Encoding', ''), 'gzip')) {
            return $response;
        }

        if ($response->headers->has('Content-Encoding') || ! $this->shouldCompress($response)) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $compressed = gzencode($content, 6);
        if ($compressed === false) {
            return $response;
        }

        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip', true);
        $response->headers->remove('Content-Length');

        $existingVary = $response->headers->get('Vary', '');
        if (! str_contains($existingVary, 'Accept-Encoding')) {
            $response->headers->set('Vary', trim($existingVary.', Accept-Encoding', ', '));
        }

        return $response;
    }

    protected function shouldCompress(Response $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $type = (string) $response->headers->get('Content-Type', '');

        return str_contains($type, 'text/html')
            || str_contains($type, 'application/json')
            || str_contains($type, 'text/plain')
            || str_contains($type, 'application/javascript')
            || str_contains($type, 'text/css')
            || str_contains($type, 'application/xml');
    }
}
