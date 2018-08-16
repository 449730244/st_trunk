<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqLetgoalhalfDetail extends Model
{
    protected $table    =   'zq_letgoalhalf_detail';

    protected  $primaryKey='ID';

    protected $fillable = [  
        "UpOdds",
        "Goal",
        "DownOdds",
        "ModifyTime",
        "isEarly",
    ];
}