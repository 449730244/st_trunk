<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ZqLetgoal;
use App\Models\ZqLetgoalhalf;
use App\Models\ZqStandard;
use App\Models\ZqTotalscore;
use App\Models\ZqTotalscorehalf;
use App\Models\ZqStandardhalf;
class getzqLetgoal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ZqLetgoal:peilv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '足球赔率';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$zl_model = new ZqLetgoal();
		$zhh_model = new ZqLetgoalhalf();
		$zsd_model = new ZqStandard();
		$ztt_model = new ZqTotalscore();
		$zts_model = new ZqTotalscorehalf();
        $bcop_model = new ZqStandardhalf();
        $zl_model->get_letgoal_data();
        $zhh_model->get_halfletgoal_data();
        $zsd_model->get_standard_data();
        $ztt_model->get_totalscore_data();
        $zts_model->get_totalscorehalf_data();
        $bcop_model->get_standardhalf_data();
    }
}
