<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqLetgoalDetail extends Model
{
    protected $table    =   'zq_letgoal_detail';

    protected  $primaryKey='id';

    protected $fillable = [  
      "Indentity",
      "OddsID",
      "UpOdds",
      "Goal",
      "DownOdds",
      "ModifyTime",
      "isEarly"
    ];
}