<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootBallTeam extends Model
{
    protected $table    =   'zq_team';

    protected  $primaryKey='TeamID';

    public  $timestamps=false;

    protected $fillable = ['TeamID','SclassID','Name_Short','Name_J','Name_F','Name_E','infoOrder','Area','Gymnasium','Flag','Capacity','Address','URL','Address','Introduce','Drillmaster','MasterPic','MasterIntro','HomePoloShirt','GuestPoloShirt','isNational','Kind'];
}
