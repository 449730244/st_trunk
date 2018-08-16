<?php
namespace App\Transformers;

use App\Models\ZqArea;
use League\Fractal\TransformerAbstract;

class ZqAreaTransformer extends TransformerAbstract
{
    public function transform(ZqArea $area)
    {
        return [
            'id' => $area->id,
            'name' => $area->name,
        ];
    }
}