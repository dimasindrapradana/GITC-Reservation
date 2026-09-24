<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_number',
        'user_id',
        'room_id',
        'training_room_id',
        'field_id',
        'starts_at',
        'ends_at',
        'instructor',
        'description',
        'status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function trainingRoom(): BelongsTo
    {
        return $this->belongsTo(TrainingRoom::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}