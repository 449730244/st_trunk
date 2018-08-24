<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 10:47
 */
namespace App\Console\Commands;

use App\Http\Controllers\GetSportController;
use Illuminate\Console\Command;

class sportData extends Command
{
    protected $signature = 'sportsevent:sportData';
    protected $description = '获取页面数据';

    public function __construct ()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('start');

        $SportData = new GetSportController();
        $SportData->getZqPlayerTech();

        $this->info('end');
    }

}