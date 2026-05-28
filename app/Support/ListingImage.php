<?php

namespace App\Support;

class ListingImage
{
    /**
     * Card-sized image URL and srcset for listing grids (mobile ~320–676px wide).
     *
     * @return array{image: ?string, image_srcset: ?string}
     */
    public static function forCard(?string $imageUrl): array
    {
        if ($imageUrl === null || $imageUrl === '') {
            return ['image' => null, 'image_srcset' => null];
        }

        if (! str_ends_with(strtolower($imageUrl), '.webp')) {
            return ['image' => $imageUrl, 'image_srcset' => null];
        }

        $cardUrl = preg_replace('/\.webp$/i', '-640.webp', $imageUrl) ?? $imageUrl;
        $relative = ltrim(str_replace('/storage/', '', $cardUrl), '/');
        $fullPath = storage_path('app/public/'.$relative);

        if (is_file($fullPath)) {
            return [
                'image' => $cardUrl,
                'image_srcset' => $cardUrl.' 640w, '.$imageUrl.' 1200w',
            ];
        }

        return ['image' => $imageUrl, 'image_srcset' => null];
    }
}
