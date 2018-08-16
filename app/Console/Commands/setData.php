<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/3
 * Time: 14:08
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SetDataController;

class setData extends Command
{
    protected $signature = 'sportsevent:setData';
    protected $description = '数据入库存储';

    public function __construct ()
    {
        parent::__construct();
    }

    public function handle()
    {
        $SetData = new SetDataController();
        $SetData->lineup();
        $SetData->PlayerTech();
        $SetData->lqSClass();
        $SetData->set_lqSchedule();
        $SetData->set_today_lqSchedule();
    }

}

