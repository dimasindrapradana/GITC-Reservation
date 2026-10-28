<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'training_program_id',
        'training_schedule_id',
        'training_room_id',
        'subject',
        'class_name',
        'instructor_name',
        'participant_count',
        'session_date',
        'start_time',
        'end_time',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'participant_count' => 'integer',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class, 'training_program_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(TrainingSchedule::class, 'training_schedule_id');
    }

    public function trainingRoom(): BelongsTo
    {
        return $this->belongsTo(TrainingRoom::class, 'training_room_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
