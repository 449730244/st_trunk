<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqSchedule extends Model
{
    protected $table = 'zq_schedule';
    protected $primaryKey = 'ScheduleID';
    protected $guarded = [];

    protected $casts = [
        'Neutrality' => 'boolean',
        'bfShow' => 'boolean',
    ];
}
