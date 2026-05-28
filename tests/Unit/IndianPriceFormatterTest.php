<?php

use App\Support\IndianPriceFormatter;

it('formats crore prices', function () {
    expect(IndianPriceFormatter::format(49_000_000))->toBe('₹ 4.9 Cr');
    expect(IndianPriceFormatter::format(10_00_00_000))->toBe('₹ 10 Cr');
});

it('formats lakh prices', function () {
    expect(IndianPriceFormatter::format(25_00_000))->toBe('₹ 25 L');
});

it('formats rental suffix', function () {
    expect(IndianPriceFormatter::format(35_000, true))->toBe('₹ 35,000 / month');
});
