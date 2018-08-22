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

class ZqPlayerTransformer extends TransformerAbstract
{
    public function transform($data)
    {
        return [
                'PlayerID' => $data->PlayerID,
//                        'Kind'=>$data->Kind,
//                        'Name_Short'=>$data->Name_J,
                'Name_F' => $data->Name_F,
                'Name_J' => $data->Name_J,
                'Name_E' => $data->Name_E,
//                        'Name_Es'=>'',
                'Birthday' => $data->Birthday,
                'Tallness' => $data->Tallness,
                'Weight' => (int)$data->Weight,
                'Country' => $data->Country,
                'Photo' => $data->Photo,
                'Introduce' => $data->Introduce,
                'Health' => $data->Health,
                'IdiomaticFeet' => $data->IdiomaticFeet,
                'ExpectedValue' => $data->ExpectedValue,
//                        'HonorInfo'=>$data->HonorInfo,
                'ModifyTime' => $data->ModifyTime,
                'TeamID' =>  $data->TeamID,
                'Place' => $data->Place,
            ];
    }

}