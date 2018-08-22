<?php

namespace App\Http\Controllers\Api;


use App\Models\LqSchedule;
use App\Transformers\LqScheduleTransformer;
use Illuminate\Http\Request;

class LqScheduleController extends Controller
{
    public function index(Request $request)
    {
        if(empty($request->route('sclass_id')))
        {
            return $this->errorResponse(404,'赛事ID不存在或为空',10006);
        }
        return $this->response->collection(LqSchedule::where(['SClassID' => $request->route('sclass_id')])->orderBy('ScheduleID','asc')->get(),new LqScheduleTransformer());
    }
}