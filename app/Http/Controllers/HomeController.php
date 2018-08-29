<?php

namespace App\Http\Controllers;

use App\Models\LqSchedule;
use App\Models\LqSclass;
use App\Models\ZqLineup;
use App\Models\ZqPlayerTech;
use App\Models\ZqSchedule;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function lineup()
    {
        $lineup = new ZqLineup();
        $lineup->getLineup();
    }

    public function playerTech()
    {
        $lineup = new ZqPlayerTech();
        $lineup->getPlayerTech();
    }

    public function getLqSclass()
    {
        $lqSclass = new LqSclass();
        $lqSclass->getSclass();
        $lqSclass->getSClassInfo();
    }

    public function getLqSchedule()
    {
        $LqSchedule = new LqSchedule();
        $LqSchedule->getSchedule();
    }

    public function getZqSchedule()
    {
        $ZqSchedule = new GetSportController();
        $ZqSchedule->getZqSchedule();
    }
}
