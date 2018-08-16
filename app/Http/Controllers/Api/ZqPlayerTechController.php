<?php

namespace App\Http\Controllers\Api;

use App\Models\ZqPlayerTech;
use App\Transformers\ZqPlayerTechTransformer;
use Illuminate\Http\Request;

class ZqPlayerTechController extends Controller
{
    /**
     * @param Request $request
     * $sclass_id 比赛ID
     */
    public function index(Request $request)
    {
        if(empty($request->input('sclass_id')))
        {
            return $this->errorResponse(404,'赛事ID不存在或为空',10002);
        }
        return $this->response->collection(ZqPlayerTech::where(['SClassID'=>$request->input('sclass_id')])->get(),new ZqPlayerTechTransformer());
    }
}
