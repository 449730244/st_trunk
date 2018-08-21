<?php

namespace App\Http\Controllers\Api;

use App\Models\LqSclass;
use App\Transformers\LqSclassTransformer;
use Illuminate\Http\Request;

class LqSclassController extends Controller
{
    public function index(Request $request)
    {
        if(empty($request->route('country_id')))
        {
            return $this->errorResponse(404,'国家ID不存在或为空',10004);
        }
        return $this->response->collection(LqSclass::where(['countryID' => $request->route('country_id')])->orderBy('SClassID','asc')->get(),new LqSclassTransformer());
    }
}