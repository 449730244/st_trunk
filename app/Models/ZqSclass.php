<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqSclass extends Model
{
    //
    protected $table = 'zq_sclass';
    protected $fillable =[
        "SClassID",
        "Color",
        "Name_J",
        "Name_F",
        "Name_E",
        "Name_JS",
        "Name_FS",
        "Name_ES",
        "Type",
        'Mode',
        "Total_Round",
        "Curr_Round",
        "Curr_matchSeason",
        "CountryID",
        "CountryName",
        "CountryEnName",
        "AreaID",
        "Logo",
        'SubSclass',
    ];
    protected $primaryKey = 'SClassID';

    public function seasons(){
        return $this->hasMany(ZqMatchSeason::class,'sclassId','SClassID');
    }

    public function schedules(){
        return $this->hasMany(ZqSchedule::class,'SclassID','SClassID');
    }

    public function subSclasses(){
        return $this->hasMany(ZqSubSclass::class,'SclassID', 'SClassID');
    }
}
