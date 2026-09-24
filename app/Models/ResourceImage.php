<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ResourceImage extends Model
{
    protected $fillable = [
        'resource_type',
        'resource_id',
        'file',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function resource(): MorphTo
    {
        return $this->morphTo();
    }
}