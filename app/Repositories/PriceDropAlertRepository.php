<?php

namespace App\Repositories;

use App\Models\PriceDropAlert;

class PriceDropAlertRepository
{
    public function upsertAlert(array $attributes): PriceDropAlert
    {
        return PriceDropAlert::query()->updateOrCreate(
            [
                'property_id' => $attributes['property_id'],
                'email' => $attributes['email'],
            ],
            [
                'phone' => $attributes['phone'] ?? null,
                'name' => $attributes['name'] ?? null,
                'active' => true,
            ]
        );
    }
}
