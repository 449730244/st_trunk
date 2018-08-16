<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Cache;

class ZqPlayerTech extends Model
{
    protected $guarded = [];
    protected $table = 'zq_playertech';

    /**
     * 获取当天比赛球员数据统计
     * url http://sapi.meme100.com/zq/PlayDetail.aspx
     */
    public function getPlayerTech()
    {
        //获取当天的所有比赛
        $playTech = [];
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/PlayDetail.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content= $response->getBody();
        $data = json_decode($content,true);
        if(empty($data['data']['match']))
        {
            Log::error('获取当天比赛数据为空');
        }else{
            if(count($data['data']['match']) != count($data['data']['match'],1))
            {
                foreach ($data['data']['match'] as $keys => $SClass)
                {
                    $ZqSClass = ZqSclass::where(["Name_JS"=>trim($SClass['league'])])->first(['SClassID']);
                    echo "开始：".$keys."-->赛事：".$ZqSClass->SClassID."--->比赛:".$SClass['ScheduleID']."\n";
                    $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
                    $response = $client->request('GET', 'zq/PlayDetail.aspx', [
                        'query' => ['token'=>'12312313123','id'=>$SClass['ScheduleID']]
                    ]);
                    $content= $response->getBody();
                    $data = json_decode($content,true);
                    Try{
                        if(empty($data['data']))
                        {
                            return "比赛ID：{$SClass['ScheduleID']}，获取的数据为空，原始数据为:".$content;
                        }else{
                            foreach($data['data']['play'] as $key => $val)
                            {
                                Log::error("开始：".$keys."-->赛事：".$ZqSClass->SClassID."--->比赛:".$SClass['ScheduleID']."\n");
                                $name_list = explode(',',$val['playerName']);
                                $playTech[$keys][$key]['SClassID']        = $ZqSClass->SClassID; //赛季ID
                                $playTech[$keys][$key]['TeamID']          = $val['TeamID']; //球队ID
                                $playTech[$keys][$key]['PlayerID']        = $val['ID']; //球员ID
                                $playTech[$keys][$key]['Name_J']          = $name_list[0]; //球员名称简体
                                $playTech[$keys][$key]['Name_F']          = $name_list[1]; //球员名称繁体
                                $playTech[$keys][$key]['number']          = $val['number']; //球衣号
                                $playTech[$keys][$key]['positionName']    = $val['positionName']; //位置
                                $playTech[$keys][$key]['shots']           = $val['shots'];       //射门次数
                                $playTech[$keys][$key]['shotsTarget']     = $val['shotsTarget']; //射正次数
                                $playTech[$keys][$key]['wasFouled']       = $val['wasFouled'];  //被犯规
                                $playTech[$keys][$key]['offside']         = $val['Offsides'];   //越位
                                $playTech[$keys][$key]['rating']          = $val['rating'];  //评分
                                $playTech[$keys][$key]['keyPass']         = $val['keyPass'];  //关键传球
                                $playTech[$keys][$key]['aerialWon']       = $val['aerialWon'];  //争项成功
                                $playTech[$keys][$key]['touches']         = $val['touches'];  //身体接触
                                $playTech[$keys][$key]['dribblesWon']     = $val['dribblesWon'];  //带球摆脱
                                $playTech[$keys][$key]['dispossessed']    = $val['dispossessed'];  //失去球权
                                $playTech[$keys][$key]['turnOver']        = $val['turnOver'];  //失误
                                $playTech[$keys][$key]['tackles']         = $val['tackles'];  //铲断
                                $playTech[$keys][$key]['interception']    = $val['interception'];  //拦截
                                $playTech[$keys][$key]['clearances']      = $val['clearances'];  //解围
                                $playTech[$keys][$key]['clearanceWon']    = $val['clearanceWon'];  //有效解围
                                $playTech[$keys][$key]['shotsBlocked']    = $val['shotsBlocked'];  //封堵
                                $playTech[$keys][$key]['OffsideProvoked'] = $val['OffsideProvoked'];  //造越位
                                $playTech[$keys][$key]['fouls']           = $val['fouls'];  //犯规
                                $playTech[$keys][$key]['totalPass']       = $val['totalPass'];  //传球
                                $playTech[$keys][$key]['PassRate']        = $val['PassRate'];  //传球成功率
                                $playTech[$keys][$key]['accuratePass']    = $val['accuratePass'];  //传球成功
                                $playTech[$keys][$key]['CrossNum']        = $val['CrossNum'];  //横传
                                $playTech[$keys][$key]['CrossWon']        = $val['CrossWon'];  //精确横传
                                $playTech[$keys][$key]['longBall']        = $val['longBall'];  //长传
                                $playTech[$keys][$key]['longBallWon']     = $val['longBallWon'];  //精确长传
                                $playTech[$keys][$key]['throughBall']     = $val['throughBall'];  //直塞
                                $playTech[$keys][$key]['throughBallWon']  = $val['throughBallWon'];  //精确直塞
                            }
                        }
                    }catch (\Exception $exception){
                        return "获取比赛数据异常".$exception->getMessage()."<br>获取的原始数据为:".json_encode($data,JSON_UNESCAPED_UNICODE);
                    }
                    sleep(15);
                }
            }else{
                $ZqSClass = ZqSclass::where(["Name_JS"=>trim($data['data']['match']['league'])])->first(['SClassID']);
                echo "开始：-->赛事：".$ZqSClass->SClassID."--->比赛:".$data['data']['match']['ScheduleID']."\n";
                $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
                $response = $client->request('GET', 'zq/PlayDetail.aspx', [
                    'query' => ['token'=>'12312313123','id'=>$data['data']['match']['ScheduleID']]
                ]);
                $content= $response->getBody();
                $data = json_decode($content,true);
                Try{
                    if(empty($data['data']))
                    {
                        return "比赛ID：{$data['data']['match']['ScheduleID']}，获取的数据为空，原始数据为:".$content;
                    }else{
                        foreach($data['data']['play'] as $key => $val)
                        {
                            Log::error("开始：-->赛事：".$ZqSClass->SClassID."--->比赛:".$data['data']['match']['ScheduleID']."\n");
                            $name_list = explode(',',$val['playerName']);
                            $playTech[$key]['SClassID']        = $ZqSClass->SClassID; //赛季ID
                            $playTech[$key]['TeamID']          = $val['TeamID']; //球队ID
                            $playTech[$key]['PlayerID']        = $val['ID']; //球员ID
                            $playTech[$key]['Name_J']          = $name_list[0]; //球员名称简体
                            $playTech[$key]['Name_F']          = $name_list[1]; //球员名称繁体
                            $playTech[$key]['number']          = $val['number']; //球衣号
                            $playTech[$key]['positionName']    = $val['positionName']; //位置
                            $playTech[$key]['shots']           = $val['shots'];       //射门次数
                            $playTech[$key]['shotsTarget']     = $val['shotsTarget']; //射正次数
                            $playTech[$key]['wasFouled']       = $val['wasFouled'];  //被犯规
                            $playTech[$key]['offside']         = $val['Offsides'];   //越位
                            $playTech[$key]['rating']          = $val['rating'];  //评分
                            $playTech[$key]['keyPass']         = $val['keyPass'];  //关键传球
                            $playTech[$key]['aerialWon']       = $val['aerialWon'];  //争项成功
                            $playTech[$key]['touches']         = $val['touches'];  //身体接触
                            $playTech[$key]['dribblesWon']     = $val['dribblesWon'];  //带球摆脱
                            $playTech[$key]['dispossessed']    = $val['dispossessed'];  //失去球权
                            $playTech[$key]['turnOver']        = $val['turnOver'];  //失误
                            $playTech[$key]['tackles']         = $val['tackles'];  //铲断
                            $playTech[$key]['interception']    = $val['interception'];  //拦截
                            $playTech[$key]['clearances']      = $val['clearances'];  //解围
                            $playTech[$key]['clearanceWon']    = $val['clearanceWon'];  //有效解围
                            $playTech[$key]['shotsBlocked']    = $val['shotsBlocked'];  //封堵
                            $playTech[$key]['OffsideProvoked'] = $val['OffsideProvoked'];  //造越位
                            $playTech[$key]['fouls']           = $val['fouls'];  //犯规
                            $playTech[$key]['totalPass']       = $val['totalPass'];  //传球
                            $playTech[$key]['PassRate']        = $val['PassRate'];  //传球成功率
                            $playTech[$key]['accuratePass']    = $val['accuratePass'];  //传球成功
                            $playTech[$key]['CrossNum']        = $val['CrossNum'];  //横传
                            $playTech[$key]['CrossWon']        = $val['CrossWon'];  //精确横传
                            $playTech[$key]['longBall']        = $val['longBall'];  //长传
                            $playTech[$key]['longBallWon']     = $val['longBallWon'];  //精确长传
                            $playTech[$key]['throughBall']     = $val['throughBall'];  //直塞
                            $playTech[$key]['throughBallWon']  = $val['throughBallWon'];  //精确直塞
                        }
                    }
                }catch (\Exception $exception){
                    return "获取比赛数据异常".$exception->getMessage()."<br>获取的原始数据为:".json_encode($data,JSON_UNESCAPED_UNICODE);
                }
                sleep(15);
            }
            $collection = collect($playTech);
            $sorted = $collection->sortBy('SClassID');
            Cache::store('file')->forever('Play_Detail', $sorted);
        }
    }
}
