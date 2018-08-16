<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/9
 * Time: 13:55
 */
namespace App\Transformers;

use App\Models\ZqLineup;
use League\Fractal\TransformerAbstract;

class ZqLineupTransformer extends TransformerAbstract
{
    public function transform(ZqLineup $zqLineup)
    {
            return [
                'ScheduleID' => $zqLineup->ScheduleID,
                'HomeLineup' => $zqLineup->HomeLineup,
                'awayLineup' => $zqLineup->awayLineup,
                'HomeLineupFirst' => $zqLineup->HomeLineupFirst,
                'AwayLineupFirst' => $zqLineup->AwayLineupFirst,
                'HomeBackup' => $zqLineup->HomeBackup,
                'AwayBackup' => $zqLineup->AwayBackup
            ];
    }
}