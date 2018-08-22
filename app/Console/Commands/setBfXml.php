<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GetData;
use App\Models\ZqSclass;
use Illuminate\Support\Facades\Log;

class setBfXml extends Command
{

    protected $signature = 'sportsevent:setBfXml';

    protected $description = '赛程数据写入数据库';

    public function __construct(GetData $getData)
    {
        parent::__construct();
        $this->getData = $getData;
    }

    public function handle()
    {
        //$startId = $this->ask('input start id');
        //$endId = $this->ask('input end id');

        Log::useFiles(storage_path('logs/BF_XML_SET.log'));
        $this->info('start');
        //foreach (ZqSclass::where('SClassID','>=',$startId)->where('SClassID','<',$endId)->orderBy('SClassID','asc')->cursor() as $sclass) {
        foreach (ZqSclass::orderBy('SClassID','asc')->cursor() as $sclass) {
            $return = $this->getData->BF_XML_to_database($sclass->SClassID);
            if ($return != 'success'){
                Log::error($return, ['sclassid' => $sclass->SClassID]);
                $this->error("sclassid:{$sclass->SClassID} fail");
            }else{
                $this->line("{$sclass->SClassID}:success");
            }
            sleep(2);
        }
        $this->info('end');
    }
}
