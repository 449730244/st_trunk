<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqStandardhalf extends Model
{
    protected $table    =   'zq_standardhalf';

    protected  $primaryKey='OddsID';

    protected $fillable = [  
	  "ScheduleID",
	  "CompanyID",
	  "FirstHomeWin",
	  "FirstStandoff",
	  "FirstGuestWin",
	  "HomeWin",
	  "Standoff",
	  "GuestWin",
	  "HomeWin_R",
	  "GuestWin_R",
	  "Standoff_R",
	  "ModifyTime"
    ];
}