<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GetData;
use App\Models\ZqSclass;
use Illuminate\Support\Facades\Log;

class getBfXml extends Command
{

    protected $signature = 'sportsevent:getBfXml';

    protected $description = '获取赛程数据';

    public function __construct(GetData $getData)
    {
        parent::__construct();
        $this->getData = $getData;
    }

    public function handle()
    {
        $startId = $this->ask('input start id');
        $endId = $this->ask('input end id');

        Log::useFiles(storage_path('logs/BF_XML.log'));
        $this->info('start');
        foreach (ZqSclass::where('SClassID','>=',$startId)->where('SClassID','<',$endId)->orderBy('SClassID','asc')->cursor() as $sclass) {
            $return = $this->getData->BF_XML($sclass->SClassID);
            if ($return != 'success'){
                Log::error($return, ['sclassid' => $sclass->SClassID]);
                $this->error("sclassid:{$sclass->SClassID} fail");
            }else{
                $this->line("{$sclass->SClassID}:success");
            }
            sleep(60);
        }
        $this->info('end');
    }
}
