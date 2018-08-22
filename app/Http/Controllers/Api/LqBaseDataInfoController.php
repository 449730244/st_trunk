<?php

namespace App\Http\Controllers\Api;

use App\Models\FootBallCountry;
use App\Models\LqJifen;
use App\Models\LqTeahnicCount;
use App\Models\LqTeam;
use App\Models\ZqSclass;
use App\Models\ZqScore;
use App\Models\ZqTopScorer;
use App\Transformers\LqJifenInfoTransformer;
use App\Transformers\LqPlayerInfoTransformer;
use App\Transformers\LqTeahnicCountTransformer;
use App\Transformers\LqteamInfoTransformer;
use App\Transformers\ZqCountryListTransformer;
use App\Transformers\ZqJifenTransformer;
use App\Transformers\ZqteamInfoTransformer;
use App\Transformers\ZqTopScorerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LqBaseDataInfoController extends Controller
{

    //球队信息
    public function teamInfo(Request $request)
    {
        return $this->response->collection(LqTeam::all(), new LqteamInfoTransformer());
    }

    //获取篮球球员基础数据
    public function playerInfo(Request $request)
    {
        $teamid = $request->teamid;
        if (empty($teamid)) {
            $this->errorResponse(422, "传入参数teamid不能为空", 200);
        }
        $data = DB::table("lq_player")->where(["TeamID"=>$teamid])->get();
        return $this->response->collection($data, new LqPlayerInfoTransformer());
    }

    //积分联盟
    public function score(Request $request)
    {

        $sclassid = $request->input('sclassid');

        if (empty($sclassid)) {
            $this->errorResponse(422, "传入参数sclassid不能为空", 200);
        }
        $sclassid = $request->input("sclassid");
        $data = LqJifen::where(["sclassid" => $sclassid])->get();

        return $this->response->collection($data, new LqJifenInfoTransformer());
    }

    //技术统计
    public function teahnicCount(Request $request)
    {
        $id = $request->input('id');//赛事id
        if (empty($id)) {
            $this->errorResponse(422, "传入参数赛事id不能为空", 200);
        }

        $data = LqTeahnicCount::where(["id" => $id])->get();

        return $this->response->collection($data, new LqTeahnicCountTransformer());
    }
}
