<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/20
 * Time: 11:15
 */
namespace  App\Transformers;


use App\Models\LqSclass;
use League\Fractal\TransformerAbstract;

class LqSclassTransformer extends TransformerAbstract
{
    public function transform(LqSclass $lqSclass)
    {
        return [
            'SClassID' => $lqSclass->SClassID,
            'Color' => trim($lqSclass->Color),
            'Name_JS' => $lqSclass->Name_JS,
            'Name_J'=> $lqSclass->Name_J,
            'Name_F'=> $lqSclass->Name_F,
            'Name_E'=> $lqSclass->Name_E,
            'type'=> $lqSclass->type,
            'Curr_matchSeason'=> $lqSclass->Curr_matchSeason,
            'countryID'=> $lqSclass->countryID,
            'country'=> $lqSclass->country,
            'curr_year' => $lqSclass->curr_year,
            'curr_month'=> $lqSclass->curr_month,
            'sclass_kind'=> $lqSclass->sclass_kind,
            'sclass_time'=> $lqSclass->sclass_time,
            'All_matchSeason'=>$lqSclass->All_matchSeason
        ];
    }
}