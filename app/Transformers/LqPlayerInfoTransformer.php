<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class LqPlayerInfoTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return
            [
                "id"=>$data->id,
                "Number"=>$data->Number,
                "Name_F"=>$data->Name_F,
                "Name_JS"=>$data->Name_JS,
                "Name_J"=>$data->Name_J,
                "Name_E"=>$data->Name_E,
                "TeamID"=>$data->TeamID,
                "Place"=>$data->Place,
                "Birthday"=>$data->Birthday,
                "Tallness"=>$data->Tallness,
                "Weight"=>$data->Weight,
                "Photo"=>$data->Photo,
                "NbaAge"=>$data->NbaAge,
            ];
    }
}