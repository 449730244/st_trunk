<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LqTotalscore extends Model
{
    protected $table    =   'lq_totalscore';

    protected  $primaryKey='OddsID';

    protected $fillable = [  
        "ScheduleID",
        "CompanyID",
        "LowOdds",
        "totalScore",
        "HighOdds",
        "ModifyTime",
        "LowOdds1",
        "LowOdds2",
        "LowOdds3",
        "LowOdds4",
        "LowOddsHalf",
        "LowOdds_R",
        "TotalScore1",
        "TotalScore2",
        "TotalScore3",
        "TotalScore4",
        "TotalScoreHalf",
        "TotalScore_R",
        "HighOdds1",
        "HighOdds2",
        "HighOdds3",
        "HighOdds4",
        "HighOddsHalf",
        "HighOdds_R",
    ];
}