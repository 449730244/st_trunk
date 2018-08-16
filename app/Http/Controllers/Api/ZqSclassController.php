<?php

namespace App\Http\Controllers\Api;

use App\Models\ZqSclass;
use App\Transformers\ZpSclassTransformer;
use Illuminate\Http\Request;

class ZqSclassController extends Controller
{
    //
    public function index(Request $request)
    {
        if(empty($request->route('country_id')))
        {
            return $this->errorResponse(404,'国家ID不存在或为空',10001);
        }
        return $this->response->collection(ZqSclass::where(['CountryID' => $request->route('country_id')])->get(),new ZpSclassTransformer());
    }
}
