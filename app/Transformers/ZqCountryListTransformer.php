<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class ZqCountryListTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return [
            'Info_type' => $data->Info_type,//区域id
            'InfoID' => $data->InfoID,//国家id
            'NameCN' => $data->NameCN,//中文名称
            'NameFN' => $data->NameFN,//中文繁体名称
            'NameEN' => $data->NameEN,//英文名称
            'FlagPic' => $data->FlagPic,
        ];
    }
}