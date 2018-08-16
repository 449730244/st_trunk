<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LqLetgoal extends Model
{
    protected $table    =   'lq_letgoal';

    protected  $primaryKey='OddsID';

    protected $fillable = [  
       "ScheduleID",
        "CompanyID",
        "HomeOdds",
        "LetGoal",
        "GuestOdds",
        "HomeOdds_F",
        "LetGoal_F",
        "GuestOdds_F",
        "ModifyTime",
        "HomeOdds_R",
        "Goal_R",
        "GuestOdds_R",
        "create_time",
        "update_time",
    ];
}