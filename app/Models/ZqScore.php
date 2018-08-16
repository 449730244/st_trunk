<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZqScore extends Model
{
    protected  $table='zq_score';
   protected $fillable=["TeamID","Sequence","Ranking","Match_total","Red_carded","Win","Draw","Lose","A","L","GD","Winning_rate","Equality_rate","Negative_rate","A_average","L_average","Total_Score","Deduct_Score","Deduct_Score_explain","Match1","Match2","Match3","Match4","Match5","Match6","Type","Season","Color","SClassID","subID"];
}
