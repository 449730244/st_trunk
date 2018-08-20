<?php

namespace App\Http\Controllers\Api;

use App\Models\LqSclassInfo;
use App\Transformers\LqSclassInfoTransformer;

class LqSclassInfoController extends Controller
{
    public function index()
    {
        return $this->response->collection(LqSclassInfo::orderBy('InfoID','asc')->get(),new LqSclassInfoTransformer());
    }
}
