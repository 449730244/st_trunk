<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/9
 * Time: 13:52
 */
namespace App\Http\Controllers\Api;

use App\Models\ZqLineup;
use App\Transformers\ZqLineupTransformer;
use Illuminate\Http\Request;

class ZqLineupController extends Controller
{
    public function index(Request $request)
    {
        if(empty($request->input('ScheduleID')))
        {
            return $this->errorResponse(404,'比赛ID不存在或为空',10001);
        }
        return $this->response->collection(ZqLineup::where(['ScheduleID' => $request->input('ScheduleID')])->get(),new ZqLineupTransformer());
    }
}

