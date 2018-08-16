<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class ZqTopScorerTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return [
            'player_J' => $data->player_J,//球员姓名
            'country' => $data->country,//国籍
            'teamName_J' => $data->teamName_J,//球队
            'totalGoals' => $data->totalGoals,//总进球
            'homeGoals' => $data->homeGoals,//主场
            'guestGoals' => $data->guestGoals,//客场
            'homePenalty' => $data->homePenalty,//主场点球数
            'guestPenalty' => $data->guestPenalty,//客场点球数
        ];
    }
}