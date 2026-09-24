<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'name',
        'capacity',
        'lcd_count',
        'whiteboard_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'lcd_count' => 'integer',
            'whiteboard_count' => 'integer',
        ];
    }

    public function images(): MorphMany
    {
        return $this->morphMany(ResourceImage::class, 'resource');
    }
    public function getMorphClass(): string
    {
        return 'rooms';
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}