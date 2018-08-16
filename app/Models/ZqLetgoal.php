<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqLetgoal extends Model
{
    protected $table    =   'zq_letgoal';

    protected  $primaryKey='Indentity';

    protected $fillable = [  
        "ScheduleID",
        "CompanyID",
        "FirstGoal",
        "FirstUpOdds",
        "FirstDownOdds",
        "Goal",
        "UpOdds",
        "DownOdds",
        "Goal_Real",
        "UpOdds_Real",
        "DownOdds_Real",
        "ModifyTime",
        "Result",
        "ClosePan",
        "ZouDi",
        "Running",
        "isStopLive"
    ];
}