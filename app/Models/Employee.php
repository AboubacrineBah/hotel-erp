<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'matricule', 'hotel_id', 'full_name', 'job_title',
        'department', 'shift_hours', 'monthly_salary', 'presence_status',
    ];

    protected $casts = [
        'monthly_salary' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
