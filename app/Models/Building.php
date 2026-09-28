<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function trainingRooms(): HasMany
    {
        return $this->hasMany(TrainingRoom::class);
    }

    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'building_coordinator_user'
        )->withTimestamps();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(
            ResourceImage::class,
            'resource'
        )->orderBy('sort_order');
    }
}