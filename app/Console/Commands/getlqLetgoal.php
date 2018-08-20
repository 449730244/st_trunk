<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LqLetgoal;
use App\Models\LqTotalscore;
use App\Models\LqEuropeodds;
use App\Models\LqLetgoalhalf;
use App\Models\LqTotalscorehalf;
class getlqLetgoal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LqLetgoal:peilv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '篮球赔率';

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
		$model = new LqLetgoal();
        $model->get_lq_letgoal_data();
        $hmodel = new LqTotalscore();
        $hmodel->get_lq_totalscore_data();
        $umodel = new LqEuropeodds();
        $umodel->get_lq_europeodds_data();
        $pmodel = new LqLetgoalhalf();
        $pmodel->get_lq_letgoalhalf_data();
        $fmodel = new LqTotalscorehalf();
        $fmodel->get_lq_totalscorehalf_data();
    }
}
