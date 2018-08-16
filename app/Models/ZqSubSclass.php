<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqSubSclass extends Model
{
    protected $table = 'zq_subsclass';
    protected $primaryKey = 'SubSclassID';
    protected $guarded = [];

    protected $casts = [
        'IsHaveScore' => 'boolean',
        'IsCurrentSclass' => 'boolean',
        'IsZu' => 'boolean',
    ];
}
