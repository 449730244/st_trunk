<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ZqSclass;

use App\Transformers\ZqSubSclassTransformer;

class ZqSubSclassController extends Controller
{
    public function index(ZqSclass $sclass, $matchSeason){
        $list = $sclass->subSclasses;
        $subSclass_list = [];
        foreach ($list as $item){
            if (!$item->includeSeason){
                continue;
            }
            $season = explode(',', $item->includeSeason);
            if (in_array($matchSeason, $season)){
                $subSclass_list[] = $item;
            }
        }

        $collect =  collect($subSclass_list);
        $sorted = $collect->sortBy('sortNumber');

        return $this->response->collection($sorted, new ZqSubSclassTransformer());
    }
}
