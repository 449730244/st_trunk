<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqTotalscorehalf extends Model
{
    protected $table    =   'zq_totalscorehalf';

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
	  "Goal_real",
	  "UpOdds_real",
	  "DownOdds_real",
	  "ModifyTime",
	  "ZouDi"
    ];
}