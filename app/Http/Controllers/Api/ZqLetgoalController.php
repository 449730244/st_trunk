<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ZqLetgoal;
use App\Models\ZqLetgoalhalf;
use App\Models\ZqStandard;
use App\Models\ZqStandardhalf;
use App\Models\ZqTotalscore;
use App\Models\ZqTotalscorehalf;


class ZqLetgoalController extends Controller
{
    public function index(){
    	$list = ZqLetgoal::all();
    	return json_encode($list);
    }
    public function halfgoal(){
        $list = ZqLetgoalhalf::all();
    	return json_encode($list);
    }
    public function standard(){
        $list = ZqStandard::all();
        return json_encode($list);
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
