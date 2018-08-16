<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqTotalscore extends Model
{
    protected $table    =   'zq_totalscore';

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
	  "Goal_real",
	  "UpOdds_real",
	  "DownOdds_real",
	  "ClosePan",
	  "Result",
	  "ModifyTime",
	  "ZouDi",
	  "isStopLive",
    ];
}