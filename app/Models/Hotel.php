<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'city',
        'country',
        'badge',
        'address',
        'description',
        'min_price',
        'image_url',
    ];

    protected $casts = [
        'min_price' => 'decimal:2',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function stockItems(): HasMany
    {
        return $this->hasMany(StockItem::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
