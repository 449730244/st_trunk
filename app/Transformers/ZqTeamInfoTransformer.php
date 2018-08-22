<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class ZqteamInfoTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return [
            'TeamID' => $data->TeamID,
            'SclassID' => (int)$data->SclassID,
            /*'Name_Short' => "",*/
            'Name_J' => $data->Name_J,
            'Name_F' => $data->Name_F,
            'Name_E' => $data->Name_E,
            'Found_date' => $data->Found_date,
            'Area' => $data->Area,
            'Gymnasium' => $data->Gymnasium,
            'Capacity' => $data->Capacity,
            'Flag' => $data->Flag,
            'Address' => $data->Address,
            'URL' => $data->URL,
            //'Introduce' => "",
            'Drillmaster' => $data->Drillmaster,
            //'MasterPic' => "",
            //'MasterIntro' => "",
            //'HomePoloShirt' => "",
            //'GuestPoloShirt' => "",
        ];
    }
}