<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class ZqCountryListTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return [
            'Info_type' => $data->Info_type,//区域id
            'NameCN' => $data->NameCN,//中文名称
            'FlagPic' => $data->FlagPic,
        ];
    }
}