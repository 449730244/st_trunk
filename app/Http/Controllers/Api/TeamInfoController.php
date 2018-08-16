<?php

namespace App\Http\Controllers\Api;

use App\Models\FootBallCountry;
use App\Models\ZqSclass;
use App\Models\ZqScore;
use App\Models\ZqTopScorer;
use App\Transformers\ZqCountryListTransformer;
use App\Transformers\ZqJifenTransformer;
use App\Transformers\ZqteamInfoTransformer;
use App\Transformers\ZqTopScorerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TeamInfoController extends Controller
{

    //球队信息
    public function zqTeamInfo(Request $request)
    {


        $sclassid = $request->sclassid;
        $data = DB::table("zq_team")
            ->rightjoin('zq_sclass', 'zq_team.SclassID', '=', 'zq_sclass.SClassID')
            ->where(["zq_sclass.SClassID" => $sclassid])
            ->select('zq_team.*')
            ->get();

        return $this->response->collection($data, new ZqteamInfoTransformer());
    }

    //射手榜
    public function zqTopScore(Request $request)
    {
        $season     =   $request->input("season");
        $sclassid   =   $request->input("SclassID");
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
        $subID  =   $request->subid;
        $where  =   !empty($subID)?["SClassID" => $SClassID, "season" => $subID]:["SClassID" => $SClassID,];
        $data       =   ZqScore::where($where)->get();

        return $this->response->collection($data, new ZqJifenTransformer());
    }

}
