<?php

namespace App\Support;

class BlogContentSanitizer
{
  /**
   * Allowed HTML tags from Quill output.
   */
    protected static string $allowed = '<p><br><strong><b><em><i><u><h1><h2><h3><h4><ul><ol><li><a><blockquote><img>';

    public static function clean(string $html): string
    {
        $cleaned = strip_tags($html, static::$allowed);

        // Remove event handlers and javascript: URLs from anchors
        $cleaned = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/href\s*=\s*["\']?\s*javascript:[^"\'>\s]*/i', 'href="#"', $cleaned) ?? $cleaned;

        return trim($cleaned);
    }
}
