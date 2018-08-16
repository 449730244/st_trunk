<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    public function index(){

        return $this->response->collection(User::all(), new UserTransformer());
    }

    public function me(){
        return $this->response->item($this->user(), new UserTransformer());
    }
}
