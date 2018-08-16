<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 10:47
 */
namespace App\Console\Commands;

use App\Models\LqSclass;
use Illuminate\Console\Command;


class getLqSClass extends Command
{
    protected $signature = 'sportsevent:getLqSClass';
    protected $description = '获取某场比赛的阵容';

    public function __construct (LqSclass $lqSclass)
    {
        parent::__construct();
        $this->lqSclass = $lqSclass;
    }

    public function handle()
    {
        $this->info('start');

        $this->lqSclass->getSclass();

        $this->info('end');
    }

}