<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ZqMatchSeason;

class ZqMatchSeasonTransformer extends TransformerAbstract
{
    public function transform(ZqMatchSeason $season)
    {
        return [
            'season' => $season->season,
            'updated_at' => $season->updated_at->toDateTimeString(),
        ];
    }
}