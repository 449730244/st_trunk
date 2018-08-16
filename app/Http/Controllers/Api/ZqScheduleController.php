<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ZqSchedule;
use App\Models\ZqSclass;
use App\Transformers\ZqScheduleTransformer;

class ZqScheduleController extends Controller
{
    public function index(ZqSclass $sclass, $matchSeason, $subSclassID = null){
        $list = $sclass->schedules->where('MatchSeason', $matchSeason)->when($subSclassID, function($query) use ($subSclassID){
            return $query->where('subSclassID', $subSclassID);
        });

        return $this->response->collection($list, new ZqScheduleTransformer());
    }
}
