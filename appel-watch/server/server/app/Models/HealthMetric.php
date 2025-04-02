<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthMetric extends Model
{
    //
    protected $fillable = [
        'user_id',
        'date',
        'steps',
        'distance_km',
        'active_minutes',
        'upload_batch_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
