<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class ZqteamInfoTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return [
            'teamid' => $data->TeamID,
            'teamname' => $data->Name_J,
            'teamlogo' => $data->Flag,
        ];
    }
}