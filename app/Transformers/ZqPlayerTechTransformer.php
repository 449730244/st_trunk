<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/30
 * Time: 10:17
 */
namespace App\Transformers;


use App\Models\ZqPlayerTech;
use League\Fractal\TransformerAbstract;

class ZqPlayerTechTransformer extends TransformerAbstract
{
    public function transform(ZqPlayerTech $zqPlayerTech)
    {
        return [
            'PlayerID' => $zqPlayerTech->PlayerID,
            'Name_J' => $zqPlayerTech->Name_J,
            'Name_F' => $zqPlayerTech->Name_F,
            'SchSum' => $zqPlayerTech->SchSum,
            'BackSum' => $zqPlayerTech->BackSum,
            'PlayingTime' => $zqPlayerTech->PlayingTime,
            'notPenaltyGoals' => $zqPlayerTech->notPenaltyGoals,
            'penaltyGoals' => $zqPlayerTech->penaltyGoals,
            'goalMinute' => $zqPlayerTech->goalMinute,
            'shots' => $zqPlayerTech->shots,
            'shotsTarget' => $zqPlayerTech->shotsTarget,
            'goalPercent' => $zqPlayerTech->goalPercent,
            'wasFouled' => $zqPlayerTech->wasFouled,
            'offside' => $zqPlayerTech->offside,
            'bestSum' => $zqPlayerTech->bestSum,
            'rating' => $zqPlayerTech->rating,
            'TeamID' => $zqPlayerTech->TeamID,
            'SClassID' => $zqPlayerTech->SClassID,
        ];
    }

}