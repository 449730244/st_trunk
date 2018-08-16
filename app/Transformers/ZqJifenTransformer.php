<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class ZqJifenTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return [
            "TeamID"=>$data->TeamID,
            "Sequence"=>$data->Sequence,
            "Ranking"=>$data->Ranking,
            "Match_total"=>$data->Match_tota,
            "Red_carded"=>$data->Red_carded,
            "Win"=>$data->Win,
            "Draw"=>$data->Draw,
            "Lose"=>$data->Lose,
            "A"=>$data->A,
            "L"=>$data->L,
            "GD"=>$data->GD,
            "Winning_rate"=>$data->Winning_rate,
            "Equality_rate"=>$data->Equality_rate,
            "Negative_rate"=>$data->Negative_rate,
            "A_average"=>$data->A_average,
            "L_average"=>$data->L_average,
            "Total_Score"=>$data->Total_Score,
            "Deduct_Score"=>$data->Deduct_Score,
            "Deduct_Score_explain"=>$data->Deduct_Score_explain,
            "Match1"=>$data->Match1,
            "Match2"=>$data->Match2,
            "Match3"=>$data->Match3,
            "Match4"=>$data->Match4,
            "Match5"=>$data->Match5,
            "Match6"=>$data->Match6,
            "Type"=>$data->Type,
            "Season"=>$data->Season,
            "Color"=>$data->Color,
            "SClassID"=>$data->SClassID,
            "subID"=>$data->subID
        ];
    }
}