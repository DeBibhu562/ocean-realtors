<?php

namespace App\Support;

final class IndianPriceFormatter
{
    public static function format(float|int|string|null $amount, bool $isRental = false): string
    {
        if ($amount === null || $amount === '') {
            return '—';
        }

        $value = (float) $amount;

        if ($value < 0) {
            return '—';
        }

        $suffix = $isRental ? ' / month' : '';

        if ($value >= 1_00_00_000) {
            $crores = $value / 1_00_00_000;

            return '₹ '.self::compactNumber($crores).' Cr'.$suffix;
        }

        if ($value >= 1_00_000) {
            $lakhs = $value / 1_00_000;

            return '₹ '.self::compactNumber($lakhs).' L'.$suffix;
        }

        return '₹ '.number_format($value, 0, '.', ',').$suffix;
    }

    private static function compactNumber(float $value): string
    {
        if ($value >= 100) {
            return (string) (int) round($value);
        }

        $rounded = round($value, 1);

        return fmod($rounded, 1.0) === 0.0
            ? (string) (int) $rounded
            : rtrim(rtrim(number_format($rounded, 1, '.', ''), '0'), '.');
    }
}
