<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertySlugHistory extends Model
{
    protected $fillable = [
        'property_id',
        'old_slug',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
