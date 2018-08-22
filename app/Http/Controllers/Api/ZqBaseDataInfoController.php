<?php

namespace App\Http\Controllers\Api;

use App\Models\FootBallCountry;
use App\Models\ZqSclass;
use App\Models\ZqScore;
use App\Models\ZqTopScorer;
use App\Transformers\ZqCountryListTransformer;
use App\Transformers\ZqJifenTransformer;
use App\Transformers\ZqPlayerTransformer;
use App\Transformers\ZqteamInfoTransformer;
use App\Transformers\ZqTopScorerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ZqBaseDataInfoController extends Controller
{

    //球队信息
    public function zqTeamInfo(Request $request)
    {
        $sclassid = $request->sclassid;

        if (empty($sclassid)) {
            $this->errorResponse(422, "传入参数sclassid不能为空", 200);
        }
        $data = DB::table("zq_team")
            ->where(["SclassID" => $sclassid])
            ->get();

/*        $data = DB::table("zq_team")
            ->rightjoin('zq_sclass', 'zq_team.SclassID', '=', 'zq_sclass.SClassID')
            ->where(["zq_sclass.SClassID" => $sclassid])
            ->select('zq_team.*')
            ->get();*/

        return $this->response->collection($data, new ZqteamInfoTransformer());
    }

    //球员信息
    public function playerInfo(Request $request)
    {
        $teamid = $request->teamid;
        if (empty($teamid)) {
            $this->errorResponse(422, "传入参数teamid不能为空", 200);
        }
        $data = DB::table("zq_player")
            ->where(["TeamID" => $teamid])
            ->get();

        return $this->response->collection($data, new ZqPlayerTransformer());
    }


    //射手榜
    public function zqTopScore(Request $request)
    {
        $season     =   $request->input("season");
        $sclassid   =   $request->input("sclassid");
        if (empty($season)||empty($sclassid)) {

            $this->errorResponse(422, "传入参数season 、sclassid不能为空", 200);
        }
        $data       =   ZqTopScorer::where(["SclassID" => $sclassid, "season" => $season])->get();

        return $this->response->collection($data, new ZqTopScorerTransformer());
    }
    //获取国家列表
    public  function  countryList(Request $request){
        $areaid  =   $request->areaid;
        if(!empty($areaid)){
        $data   =   FootBallCountry::where(["Info_type"=>$areaid])->get();
        }else{
            $data   =   FootBallCountry::all();
        }
        return $this->response->collection($data, new ZqCountryListTransformer());
    }


    //积分数据
    public function zqJifen(Request $request){
        $SClassID  =   $request->sclassid;
        if (empty($SClassID)) {
            $this->errorResponse(422, "传入参数sclassid不能为空", 200);
        }

        $data       =   ZqScore::where( ["SClassID" => $SClassID,])->get();

        return $this->response->collection($data, new ZqJifenTransformer());
    }

}
