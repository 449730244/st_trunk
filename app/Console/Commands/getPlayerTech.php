<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/31
 * Time: 17:57
 */
namespace App\Console\Commands;

use App\Models\ZqPlayerTech;
use Illuminate\Console\Command;

class getPlayerTech extends Command
{
    protected $signature = 'sportsevent:getPlayerTech';
    protected $description = '某场比赛球员数据统计';

    public function __construct(ZqPlayerTech $zqPlayerTech)
    {
        parent::__construct();
        $this->zqPlayerTech = $zqPlayerTech;
    }

    public function handle()
    {
        $this->info('start');
        $this->zqPlayerTech->getPlayerTech();
        $this->info('end');
    }

}