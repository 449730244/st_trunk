<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Cache;

class ZqLineup extends Model
{
    //
    protected $fillable = [
        'ScheduleID',
        'HomeLineup',
        'awayLineup',
        'HomeLineupFirst',
        'AwayLineupFirst',
        'HomeBackup',
        'AwayBackup'
    ];
    protected $table = 'zq_lineup';

    /**
     * 获取某场比赛的阵容
     * $ScheduleID 比赛ID
     */
    public function getLineup()
    {
        Log::useFiles(storage_path('logs/ZqLineup.log'));

        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/lineup.aspx', [
            'query' => ['cmd'=>'new','token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $data = json_decode($content,true);
        $lineup_data =[];
        Try{
            if(empty($data['data']))
            {
                Log::error("获取的数据为空，原始数据为:".$content);
            }else{
                if(count($data['data']['match']) != count($data['data']['match'],1))
                {
                    foreach ($data['data']['match'] as $key => $val)
                    {
                        $lineup_data[$key]['ScheduleID'] = $val['ID'];
                        $lineup_data[$key]['HomeLineup'] = $this->checkEmpty($val['homeArray']);
                        $lineup_data[$key]['awayLineup'] = $this->checkEmpty($val['awayArray']);
                        $lineup_data[$key]['HomeLineupFirst'] = $this->dataExplode($val['HomeLineup']);
                        $lineup_data[$key]['AwayLineupFirst'] = $this->dataExplode($val['AwayLineup']);
                        $lineup_data[$key]['HomeBackup'] = $this->dataExplode($val['HomeBackup']);
                        $lineup_data[$key]['AwayBackup'] = $this->dataExplode($val['AwayBackup']);
                        echo "获取：".$val['ID']."成功\n";
                    }
                }else{
                    $lineup_data['ScheduleID'] = $data['data']['match']['ID'];
                    $lineup_data['HomeLineup'] = $this->checkEmpty($data['data']['match']['homeArray']);
                    $lineup_data['awayLineup'] = $this->checkEmpty($data['data']['match']['awayArray']);
                    $lineup_data['HomeLineupFirst'] = $this->dataExplode($data['data']['match']['HomeLineup']);
                    $lineup_data['AwayLineupFirst'] = $this->dataExplode($data['data']['match']['AwayLineup']);
                    $lineup_data['HomeBackup'] = $this->dataExplode($data['data']['match']['HomeBackup']);
                    $lineup_data['AwayBackup'] = $this->dataExplode($data['data']['match']['AwayBackup']);
                }

                $collection = collect($lineup_data);
                $sorted = $collection->sortBy('ScheduleID');
                Cache::store('file')->forever('Zq_Lineup', $sorted);
            }
        }catch(\Exception $exception){
            return "获取数据异常".$exception->getMessage()."<br>获取的原始数据为:".json_encode($data,JSON_UNESCAPED_UNICODE);
        }
    }


    public function dataExplode($string)
    {
            $new_str = explode(';',$string);
            $array = ['Id','Name_J','Name_F','Number','Position'];
            $new_data = [];
            $data  =[];
            foreach($new_str as $v)
            {
                $new_data[] = explode(',',$v);
            }
            foreach($new_data as $key => $val){
                if(count($val) == 6)
                {
                    $val[2] = $val[2]."·".$val[3];
                    $val[3] = $val[4];
                    $val[4] = $val[5];
                    unset($val[5]);
                }
                $data[$key] = array_combine($array,$val);
            }
            return json_encode($data);
    }
    public function checkEmpty($value)
    {
        return !empty($value) ? (is_array($value) || is_object($value) ? json_encode($value) : trim($value)) : null;
    }
}
