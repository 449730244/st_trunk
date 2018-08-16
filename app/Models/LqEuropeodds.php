<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LqEuropeodds extends Model
{
    protected $table    =   'lq_europeodds';

    protected  $primaryKey='identity';

    protected $fillable = [  
        "CompanyID",
        "ScheduleID",
        "HomeTeamID",
        "GuestTeamID",
        "HomeTeam",
        "GuestTeam",
        "FirstHomeWin",
        "FirstGuestWin",
        "HomeWin",
        "GuestWin",
        "FirstHomeWinRate",
        "FirstGuestWinRate",
        "FristbackRate",
        "HomeWinRate",
        "GuestWinRate",
        "backRate",
        "kaili1",
        "kaili2",
        "time1",
        "OddsID",
        "CompanyName",
        "time2", 
    ];
}