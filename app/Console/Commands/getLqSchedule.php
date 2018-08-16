<?php

namespace App\Console\Commands;

use App\Models\LqSchedule;
use Illuminate\Console\Command;

class getLqSchedule extends Command
{

    protected $signature = 'sportsevent:getLqSchedule';

    protected $description = '获取赛程数据';

    public function __construct(LqSchedule $lqSchedule)
    {
        parent::__construct();
        $this->lqSchedule = $lqSchedule;
    }

    public function handle()
    {
        $this->info('start');

        $this->lqSchedule->getSchedule();

        $this->info('end');
    }
}
