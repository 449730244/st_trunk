<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootBallCountry extends Model
{
    protected  $table='zq_sclassinfo';

    protected  $primaryKey='InfoID';

    public  $timestamps=false;

    protected $fillable = ['InfoID','NameCN','NameFN','NameEN','FlagPic','infoOrder','Info_type','modifyTime','allOrder'];

}
