<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootBallPlayer extends Model
{
    protected $table    =   'zq_player';

    protected  $primaryKey='PlayerID';

    public  $timestamps=false;

    protected $fillable = ['PlayerID','Kind','Name_Short','Name_J','Name_F','Name_E','Name_Es','Birthday','Tallness','Weight','Country','Photo','Introduce','Health','IdiomaticFeet','ExpectedValue','HonorInfo','ModifyTime','TeamID','Place'];
}
