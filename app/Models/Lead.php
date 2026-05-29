<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'agent_id',
        'name',
        'phone',
        'email',
        'is_real_estate_agent',
        'message',
        'visit_date',
        'source',
        'contact_channel',
        'status',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'is_real_estate_agent' => 'boolean',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
