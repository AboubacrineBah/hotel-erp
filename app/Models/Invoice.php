<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'booking_id',
        'amount_ht',
        'tva_rate',
        'tva_amount',
        'amount_ttc',
        'payment_status',
        'issued_at',
    ];

    protected $casts = [
        'amount_ht' => 'decimal:2',
        'tva_rate' => 'decimal:2',
        'tva_amount' => 'decimal:2',
        'amount_ttc' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
