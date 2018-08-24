<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ZqLetgoal;
use App\Models\ZqLetgoalhalf;
use App\Models\ZqStandard;
use App\Models\ZqStandardDetail;
use App\Models\ZqStandardhalf;
use App\Models\ZqTotalscore;
use App\Models\ZqTotalscorehalf;


class ZqLetgoalController extends Controller
{
    public function index(){
    	$list = ZqLetgoal::all();
    	return json_encode($list);
    }
    // 获取比赛的让球及大小球赔率接口
    public function zqletgoalinfo($ScheduleID){
        if ($ScheduleID < 1) return json_encode('参数有误！');
        $model = new ZqLetgoal();
        $model_score = new ZqTotalscore();
        $model->writedata($ScheduleID);
        $model_score->writedata($ScheduleID);
        $data_rangqiu = ZqLetgoal::where('ScheduleID',$ScheduleID)->get();
        $data_daxiaoqiu = ZqTotalscore::where('ScheduleID',$ScheduleID)->get();

        $data_rangqiu = json_decode(json_encode($data_rangqiu),true);
        $data_daxiaoqiu = json_decode(json_encode($data_daxiaoqiu),true);

        $data['rangqiu'] = $data_rangqiu;
        $data['daxiaoqiu'] = $data_daxiaoqiu;
        return json_encode($data);
    }
    // 获取比赛的半场让球及半场大小球赔率接口
    public function halfballinfo($ScheduleID){
        if ($ScheduleID < 1) return json_encode('参数有误！');
        $model = new ZqLetgoalhalf();
        $model_score = new ZqTotalscorehalf();
        $model->writedata($ScheduleID);
        $model_score->writedata($ScheduleID);
        $data_rangqiu = ZqLetgoalhalf::where('ScheduleID',$ScheduleID)->get();
        $data_daxiaoqiu = ZqTotalscorehalf::where('ScheduleID',$ScheduleID)->get();

        $data_rangqiu = json_decode(json_encode($data_rangqiu),true);
        $data_daxiaoqiu = json_decode(json_encode($data_daxiaoqiu),true);

        $data['rangqiu'] = $data_rangqiu;
        $data['daxiaoqiu'] = $data_daxiaoqiu;
        return json_encode($data);
    }
    public function halfgoal(){
        $list = ZqLetgoalhalf::all();
    	return json_encode($list);
    }
    public function standard(){
        $list = ZqStandard::all();
        return json_encode($list);
    }
    public function standardinfo($ScheduleID){
        if ($ScheduleID < 1) return json_encode('参数有误！');
        $model = new ZqStandard();
        $model_detail = new ZqStandardDetail();
        $model->writedata($ScheduleID);
        $model_detail->writedata($ScheduleID);
        $data = ZqStandard::where('ScheduleID',$ScheduleID)->get();
        $data = json_decode(json_encode($data),true);
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['detail'] = json_decode(json_encode(ZqStandardDetail::where('OddsID',$v['Indentity'])->get()),true);
            }
        }
        return json_encode($data);
    }
    public function standardhalf(){
        $list = ZqStandardhalf::all();
        return json_encode($list);
    }
    public function totalscore(){
        $list = ZqTotalscore::all();
        return json_encode($list);
    }
    public function totalscorehalf(){
        $list = ZqTotalscorehalf::all();
        return json_encode($list);
    }
    
}
