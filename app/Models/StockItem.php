<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockItem extends Model
{
    protected $fillable = [
        'sku', 'hotel_id', 'name', 'category',
        'quantity', 'alert_threshold', 'unit_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'alert_threshold' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->alert_threshold;
    }
}
