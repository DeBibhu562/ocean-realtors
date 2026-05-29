<?php

namespace App\Support;

class ListingImage
{
    /** @var list<int> */
    private const CARD_WIDTHS = [480, 640, 960];

    /** @var list<int> */
    private const DEFAULT_WIDTH_PRIORITY = [640, 480, 960];

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

        $relative = ltrim(str_replace('/storage/', '', $imageUrl), '/');
        $basePath = storage_path('app/public/'.$relative);
        $availableVariants = [];

        foreach (self::CARD_WIDTHS as $width) {
            $variantUrl = preg_replace('/\.webp$/i', '-'.$width.'.webp', $imageUrl) ?? $imageUrl;
            $variantRelative = ltrim(str_replace('/storage/', '', $variantUrl), '/');
            $variantPath = storage_path('app/public/'.$variantRelative);

            if (is_file($variantPath)) {
                $availableVariants[$width] = $variantUrl;
            }
        }

        $srcsetParts = [];
        foreach (self::CARD_WIDTHS as $width) {
            if (isset($availableVariants[$width])) {
                $srcsetParts[] = $availableVariants[$width].' '.$width.'w';
            }
        }

        if (is_file($basePath)) {
            $srcsetParts[] = $imageUrl.' 1200w';
        }

        if ($srcsetParts === []) {
            return ['image' => $imageUrl, 'image_srcset' => null];
        }

        $defaultImage = $imageUrl;
        foreach (self::DEFAULT_WIDTH_PRIORITY as $width) {
            if (isset($availableVariants[$width])) {
                $defaultImage = $availableVariants[$width];
                break;
            }
        }

        return [
            'image' => $defaultImage,
            'image_srcset' => implode(', ', $srcsetParts),
        ];
    }
}
