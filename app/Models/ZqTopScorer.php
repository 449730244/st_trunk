<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqTopScorer extends Model
{
    protected $table = 'zq_topscorer';
    protected $fillable = ["playerID","player_F","player_J","player_E","country","teamID","teamName_F","teamName_J","teamName_E","totalGoals","homeGoals","guestGoals","homePenalty","guestPenalty","season","SclassID"];
}
