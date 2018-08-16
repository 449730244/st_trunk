<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ZqSclass;
use App\Transformers\ZqMatchSeasonTransformer;

class ZqMatchSeasonsController extends Controller
{
    public function index(ZqSclass $sclass, Request $request){
        return $this->response->collection($sclass->seasons, new ZqMatchSeasonTransformer());
    }
}
