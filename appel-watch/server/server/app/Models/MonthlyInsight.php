<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyInsight extends Model
{
    //
    protected $fillable = [
        'user_id',
        'average_steps',
        'average_distance_km',
        'average_active_minutes',
        'step_increase_probability',
        'distance_increase_probability',
        'active_minutes_increase_probability',
        'focus_area',
        'recommendations',
        'raw_json',
    ];

    protected $casts = [
        'recommendations' => 'array',
        'raw_json' => 'json',
    ];
}
