<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LqTotalscorehalf extends Model
{
    protected $table    =   'lq_totalscorehalf';

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
    ];
}