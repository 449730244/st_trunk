<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 10:47
 */
namespace App\Console\Commands;

use App\Models\ZqLineup;
use Illuminate\Console\Command;


class getLineup extends Command
{
    protected $signature = 'sportsevent:getLineup';
    protected $description = '获取某场比赛的阵容';

    public function __construct (ZqLineup $zqLineup)
    {
        parent::__construct();
        $this->zqlineup = $zqLineup;
    }

    public function handle()
    {
        $this->info('start');

        $this->zqlineup->getLineup();

        $this->info('end');
    }

}