<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSessionTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'session_number',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'session_number' => 'integer',
        ];
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }
}
