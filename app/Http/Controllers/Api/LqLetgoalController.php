<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\LqLetgoal;
use App\Models\LqTotalscore;
use App\Models\LqEuropeodds;
use App\Models\LqEuropeoddsdetail;
use App\Models\LqLetgoalhalf;
use App\Models\LqTotalscorehalf;


class LqLetgoalController extends Controller
{
    public function index(){
    	$list = LqLetgoal::all();
    	return json_encode($list);
    }
    public function totalscore(){
        $list = LqTotalscore::all();
    	return json_encode($list);
    }
    public function europeodds(){
        $list = LqEuropeodds::all();
        return json_encode($list);
    }
    public function lqeuropeoddsinfo($ScheduleID){
        if ($ScheduleID < 1) return json_encode('参数有误！');
        $model = new LqEuropeodds();
        $model_detail = new LqEuropeoddsdetail();
        $model->writedata($ScheduleID);
        $model_detail->writedata($ScheduleID);
        $data = LqEuropeodds::where('ScheduleID',$ScheduleID)->get();
        $data = json_decode(json_encode($data),true);
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['detail'] = json_decode(json_encode(LqEuropeoddsdetail::where('OddsID',$v['OddsID'])->get()),true);
            }
        }
        return json_encode($data);
    }
    
    public function letgoalhalf(){
        $list = LqLetgoalhalf::all();
        return json_encode($list);
    }
    public function totalscorehalf(){
        $list = LqTotalscorehalf::all();
        return json_encode($list);
    }
}
