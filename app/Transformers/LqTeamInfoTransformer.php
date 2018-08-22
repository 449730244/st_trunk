<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class LqteamInfoTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return
             [
            "id"=>$data->id,
            "lsID"=>$data->lsID,
            "Name_JS"=>$data->Name_JS,
            "Name_J"=>$data->Name_J,
            "Name_F"=>$data->Name_F,
            "Name_E"=>$data->Name_E,
            "Logo"=>$data->Logo,
            "URL"=>$data->URL,
            "LocationID"=>(int)$data->LocationID,
            "MatchAddrID"=>(int)$data->MatchAddrID,
            "Gymnasium"=>$data->Gymnasium,
            "City"=>$data->City,
            "Capacity"=>$data->Capacity,
            "JoinYear"=>(int)$data->JoinYear,
            "FirstTime"=>(int)$data->FirstTime,
            "Drillmaster"=>$data->Drillmaster,
        ];
    }
}