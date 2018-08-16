<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 11:13
 */
namespace App\Transformers;

use App\Models\ZqSclass;
use League\Fractal\TransformerAbstract;
class ZpSclassTransformer extends TransformerAbstract
{
    public function transform(ZqSclass $zqSclass)
    {
        return [
            'SClassID' => $zqSclass->SClassID,
            'Name_JS' => $zqSclass->Name_JS,
        ];
    }
}
