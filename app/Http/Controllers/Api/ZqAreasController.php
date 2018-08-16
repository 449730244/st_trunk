<?php

namespace App\Http\Controllers\Api;

use App\Models\ZqArea;
use Illuminate\Http\Request;
use App\Transformers\ZqAreaTransformer;

class ZqAreasController extends Controller
{
    public function index(Request $request){
        $list = ZqArea::query()->orderBy('id','asc')->get();
        return $this->response->collection($list, new ZqAreaTransformer());
    }


}
