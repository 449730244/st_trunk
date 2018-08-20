<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/20
 * Time: 11:15
 */
namespace App\Transformers;


use App\Models\LqSclassInfo;
use League\Fractal\TransformerAbstract;

class LqSclassInfoTransformer extends TransformerAbstract
{
    public function transform(LqSclassInfo $lqSclassInfo)
    {
        return [
            'InfoID' => $lqSclassInfo->InfoID,
            'NameCN' => $lqSclassInfo->NameCN,
            'NameFN' => $lqSclassInfo->NameFN,
            'NameEN'=> $lqSclassInfo->NameEN,
            'FlagPic'=> $lqSclassInfo->FlagPic
        ];
    }
}