<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqLetgoalhalf extends Model
{
    protected $table    =   'zq_letgoalhalf';

    protected  $primaryKey='OddsID';

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
          "ZouDi"
    ];
}