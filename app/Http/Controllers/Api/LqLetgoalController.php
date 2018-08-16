<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\LqLetgoal;
use App\Models\LqTotalscore;
use App\Models\LqEuropeodds;
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
    public function letgoalhalf(){
        $list = LqLetgoalhalf::all();
        return json_encode($list);
    }
    public function totalscorehalf(){
        $list = LqTotalscorehalf::all();
        return json_encode($list);
    }
}
