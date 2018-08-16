<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LqLetgoalhalf extends Model
{
    protected $table    =   'lq_letgoalhalf';

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