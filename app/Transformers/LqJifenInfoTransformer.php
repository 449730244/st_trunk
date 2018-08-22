<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class LqJifenInfoTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return

          [
                "TeamID"=>$data->TeamID,
                "league"=>$data->league,
                "Name"=>$data->Name,
                "matchSeason"=>$data->matchSeason,
                "homewin"=>$data->homewin,
                "homeloss"=>$data->homeloss,
                "awaywin"=>$data->awaywin,
                "awayloss"=>$data->awayloss,
                "WinScale"=>(int)$data->WinScale,
                "state"=>(int)$data->state,
                "homeOrder"=>$data->homeOrder,
                "awayOrder"=>$data->awayOrder,
                "TotalOrder"=>$data->TotalOrder,
                "HomeScore"=>(int)$data->HomeScore,
                "HomeLossScore"=>(int)$data->HomeLossScore,
                "awayScore"=>$data->awayScore,
                "awayLossScore"=>$data->awayLossScore,
                "Near10Win"=>$data->Near10Win,
                "Near10loss"=>$data->Near10loss,
            ];
    }
}