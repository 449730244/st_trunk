<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Support\Facades\Log;

class LqSchedule extends Model
{
    //
    protected $guarded =[];
    protected $table = 'lq_schedule';
    protected $primaryKey ='ScheduleID';

    public function getSchedule()
    {
        try{
            Log::useFiles(storage_path('logs/getSchedule.log'));
            $schedule = [];
            foreach (LqSclass::orderBy('SClassID','asc')->cursor() as $k => $sclass)
            {
                $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
                $response = $client->request('get','lq/LqSchedule.aspx', [
                    'query' => ['token'=>'12312313123','sclassid' => $sclass->SClassID]
                ]);
                $content = $response->getBody();
                $data = json_decode($content,true);
                if(empty($data['data']))
                {
                    echo "获取赛事{$sclass->SClassID}的数据为空\n";
                    Log::info("获取赛事{$sclass->SClassID}的数据为空\n");
                }else{
                    $match = $data['data']['h'];
                    if(is_array($match))
                    {
                        foreach ($match as $key => $item)
                        {
                            $arr = explode('^',$item);
                            $SClassName = explode(',',$arr[3]); //联赛名称
                            $HomeName = explode(',',$arr[10]);   //主队名称
                            $GuestName = explode(',',$arr[12]);  //客队名称
                            $schedule[$k][$key]['ScheduleID'] = $arr[0];
                            $schedule[$k][$key]['SClassID'] = $arr[1];
                            $schedule[$k][$key]['SClassType'] = $arr[2];
                            $schedule[$k][$key]['SClassName_J'] = $SClassName[0];
                            $schedule[$k][$key]['SClassName_F'] = $SClassName[1];
                            $schedule[$k][$key]['MatchNumber'] = $arr[4];
                            $schedule[$k][$key]['Color'] = $arr[5];
                            $schedule[$k][$key]['MatchTime'] = $arr[6];
                            $schedule[$k][$key]['MatchState'] = $arr[7];
                            $schedule[$k][$key]['MatchNumberTime'] = $arr[8];
                            $schedule[$k][$key]['HomeTeamID'] = $arr[9];
                            $schedule[$k][$key]['HomeTeamName_J'] = $HomeName[0];
                            $schedule[$k][$key]['HomeTeamName_F'] = $HomeName[1];
                            $schedule[$k][$key]['GuestTeamID'] = $arr[11];
                            $schedule[$k][$key]['GuestTeamName_J'] = $GuestName[0];
                            $schedule[$k][$key]['GuestTeamName_F'] = $GuestName[1];
                            $schedule[$k][$key]['HomeTeamRank'] = $arr[13];
                            $schedule[$k][$key]['GuestTeamRank'] = $arr[14];
                            $schedule[$k][$key]['HomeTeamScore'] = $arr[15];
                            $schedule[$k][$key]['GuestTeamScore'] = $arr[16];
                            $schedule[$k][$key]['HomeOneScore'] = $arr[17];
                            $schedule[$k][$key]['HomeTwoScore'] = $arr[18];
                            $schedule[$k][$key]['HomeThreeScore'] = $arr[19];
                            $schedule[$k][$key]['HomeFourScore'] = $arr[20];
                            $schedule[$k][$key]['GuestOneScore'] = $arr[21];
                            $schedule[$k][$key]['GuestTwoScore'] = $arr[22];
                            $schedule[$k][$key]['GuestThreeScore'] = $arr[23];
                            $schedule[$k][$key]['GuestFourScore'] = $arr[24];
                            $schedule[$k][$key]['OverTimeNumber'] = $arr[25];
                            $schedule[$k][$key]['HomeOneOverTimeScore'] = $arr[26];
                            $schedule[$k][$key]['HomeTwoOverTimeScore'] = $arr[27];
                            $schedule[$k][$key]['HomeThreeOverTimeScore'] = $arr[28];
                            $schedule[$k][$key]['GuestOneOverTimeScore'] = $arr[29];
                            $schedule[$k][$key]['GuestTwoOverTimeScore'] = $arr[30];
                            $schedule[$k][$key]['GuestThreeOverTimeScore'] = $arr[31];
                            $schedule[$k][$key]['TechnicalStatistics'] = $arr[32];
                            $schedule[$k][$key]['TVShow'] = $arr[33];
                            $schedule[$k][$key]['TVRemark'] = $arr[34];
                            $schedule[$k][$key]['Neutral'] = $arr[35];
                            $schedule[$k][$key]['Season'] = $arr[36];
                            $schedule[$k][$key]['MatchType'] = $arr[37];
                            $schedule[$k][$key]['MatchAddress'] = $arr[38];
                            $schedule[$k][$key]['MatchCate'] = $arr[39];
                            $schedule[$k][$key]['MatchSubSClass'] = $arr[40];
                        }
                    }else{
                        $arr = explode('^',$match);
                        $SClassName = explode(',',$arr[3]); //联赛名称
                        $HomeName = explode(',',$arr[10]);   //主队名称
                        $GuestName = explode(',',$arr[12]);  //客队名称
                        $schedule[$k][]['ScheduleID'] = $arr[0];
                        $schedule[$k][]['SClassID'] = $arr[1];
                        $schedule[$k][]['SClassType'] = $arr[2];
                        $schedule[$k][]['SClassName_J'] = $SClassName[0];
                        $schedule[$k][]['SClassName_F'] = $SClassName[1];
                        $schedule[$k][]['MatchNumber'] = $arr[4];
                        $schedule[$k][]['Color'] = $arr[5];
                        $schedule[$k][]['MatchTime'] = $arr[6];
                        $schedule[$k][]['MatchState'] = $arr[7];
                        $schedule[$k][]['MatchNumberTime'] = $arr[8];
                        $schedule[$k][]['HomeTeamID'] = $arr[9];
                        $schedule[$k][]['HomeTeamName_J'] = $HomeName[0];
                        $schedule[$k][]['HomeTeamName_F'] = $HomeName[1];
                        $schedule[$k][]['GuestTeamID'] = $arr[11];
                        $schedule[$k][]['GuestTeamName_J'] = $GuestName[0];
                        $schedule[$k][]['GuestTeamName_F'] = $GuestName[1];
                        $schedule[$k][]['HomeTeamRank'] = $arr[13];
                        $schedule[$k][]['GuestTeamRank'] = $arr[14];
                        $schedule[$k][]['HomeTeamScore'] = $arr[15];
                        $schedule[$k][]['GuestTeamScore'] = $arr[16];
                        $schedule[$k][]['HomeOneScore'] = $arr[17];
                        $schedule[$k][]['HomeTwoScore'] = $arr[18];
                        $schedule[$k][]['HomeThreeScore'] = $arr[19];
                        $schedule[$k][]['HomeFourScore'] = $arr[20];
                        $schedule[$k][]['GuestOneScore'] = $arr[21];
                        $schedule[$k][]['GuestTwoScore'] = $arr[22];
                        $schedule[$k][]['GuestThreeScore'] = $arr[23];
                        $schedule[$k][]['GuestFourScore'] = $arr[24];
                        $schedule[$k][]['OverTimeNumber'] = $arr[25];
                        $schedule[$k][]['HomeOneOverTimeScore'] = $arr[26];
                        $schedule[$k][]['HomeTwoOverTimeScore'] = $arr[27];
                        $schedule[$k][]['HomeThreeOverTimeScore'] = $arr[28];
                        $schedule[$k][]['GuestOneOverTimeScore'] = $arr[29];
                        $schedule[$k][]['GuestTwoOverTimeScore'] = $arr[30];
                        $schedule[$k][]['GuestThreeOverTimeScore'] = $arr[31];
                        $schedule[$k][]['TechnicalStatistics'] = $arr[32];
                        $schedule[$k][]['TVShow'] = $arr[33];
                        $schedule[$k][]['TVRemark'] = $arr[34];
                        $schedule[$k][]['Neutral'] = $arr[35];
                        $schedule[$k][]['Season'] = $arr[36];
                        $schedule[$k][]['MatchType'] = $arr[37];
                        $schedule[$k][]['MatchAddress'] = $arr[38];
                        $schedule[$k][]['MatchCate'] = $arr[39];
                        $schedule[$k][]['MatchSubSClass'] = $arr[40];
                    }
                    echo "获取赛事{$sclass->SClassID}的数据成功\n";
                    Log::info("获取赛事{$sclass->SClassID}的数据成功\n");
                }
                sleep(90);
            }
            $collection = collect($schedule);
            $sorted = $collection->sortBy('ScheduleID');
            Cache::store('file')->forever('Schedule_list', $sorted);
        }catch (\Exception $exception){
            Log::info("获取比赛数据异常".$exception->getMessage()."<br>获取的原始数据为:".json_encode($data,JSON_UNESCAPED_UNICODE)."\n");
        }
    }

    public function getTodaySchedule()
    {
        try{
            Log::useFiles(storage_path('logs/getTodaySchedule.log'));
            $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
            $response = $client->request('get','lq/today.aspx', [
                'query' => ['token'=>'12312313123']
            ]);
            $content = $response->getBody();
            $data = json_decode($content);
            $match = $data->data->h;
            $schedule = [];
            foreach ($match as $key => $item)
            {
                $arr = explode('^',$item);
                $SClassName = explode(',',$arr[3]); //联赛名称
                $HomeName = explode(',',$arr[10]);   //主队名称
                $GuestName = explode(',',$arr[12]);  //客队名称
                $schedule[$key]['ScheduleID'] = $arr[0];
                $schedule[$key]['SClassID'] = $arr[1];
                $schedule[$key]['SClassType'] = $arr[2];
                $schedule[$key]['SClassName_J'] = $SClassName[0];
                $schedule[$key]['SClassName_F'] = $SClassName[1];
                $schedule[$key]['MatchNumber'] = $arr[4];
                $schedule[$key]['Color'] = $arr[5];
                $schedule[$key]['MatchTime'] = $arr[6];
                $schedule[$key]['MatchState'] = $arr[7];
                $schedule[$key]['MatchNumberTime'] = $arr[8];
                $schedule[$key]['HomeTeamID'] = $arr[9];
                $schedule[$key]['HomeTeamName_J'] = $HomeName[0];
                $schedule[$key]['HomeTeamName_F'] = $HomeName[1];
                $schedule[$key]['GuestTeamID'] = $arr[11];
                $schedule[$key]['GuestTeamName_J'] = $GuestName[0];
                $schedule[$key]['GuestTeamName_F'] = $GuestName[1];
                $schedule[$key]['HomeTeamRank'] = $arr[13];
                $schedule[$key]['GuestTeamRank'] = $arr[14];
                $schedule[$key]['HomeTeamScore'] = $arr[15];
                $schedule[$key]['GuestTeamScore'] = $arr[16];
                $schedule[$key]['HomeOneScore'] = $arr[17];
                $schedule[$key]['HomeTwoScore'] = $arr[18];
                $schedule[$key]['HomeThreeScore'] = $arr[19];
                $schedule[$key]['HomeFourScore'] = $arr[20];
                $schedule[$key]['GuestOneScore'] = $arr[21];
                $schedule[$key]['GuestTwoScore'] = $arr[22];
                $schedule[$key]['GuestThreeScore'] = $arr[23];
                $schedule[$key]['GuestFourScore'] = $arr[24];
                $schedule[$key]['OverTimeNumber'] = $arr[25];
                $schedule[$key]['HomeOneOverTimeScore'] = $arr[26];
                $schedule[$key]['HomeTwoOverTimeScore'] = $arr[27];
                $schedule[$key]['HomeThreeOverTimeScore'] = $arr[28];
                $schedule[$key]['GuestOneOverTimeScore'] = $arr[29];
                $schedule[$key]['GuestTwoOverTimeScore'] = $arr[30];
                $schedule[$key]['GuestThreeOverTimeScore'] = $arr[31];
                $schedule[$key]['TechnicalStatistics'] = $arr[32];
                $schedule[$key]['TVShow'] = $arr[33];
                $schedule[$key]['TVRemark'] = $arr[34];
                $schedule[$key]['Neutral'] = $arr[35];
                Log::info("获取比赛ID：".$arr[0]."数据成功\n");
            }
            $collection = collect($schedule);
            $sorted = $collection->sortBy('ScheduleID');
            Cache::store('file')->forever('TodayScheduleList', $sorted);
        }catch(\Exception $exception){
            Log::info("获取今日比赛数据异常".$exception->getMessage()."<br>获取的原始数据为:".json_encode($data,JSON_UNESCAPED_UNICODE)."\n");
        }
    }



}
