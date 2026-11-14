<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
        'size',
        'capacity',
        'price_per_night',
        'available_count',
        'perks',
        'image_url',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'price_per_night' => 'decimal:2',
        'available_count' => 'integer',
        'perks' => 'array',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
