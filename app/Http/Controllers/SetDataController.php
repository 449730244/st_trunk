<?php

namespace App\Http\Controllers;

use App\Models\LqSchedule;
use App\Models\LqSclass;
use App\Models\LqSclassInfo;
use App\Models\ZqLineup;
use App\Models\ZqPlayerTech;
use Illuminate\Http\Request;
use Cache;
use App\Models\FootBallCountry;
use App\Models\ZqSclass;
use App\Models\ZqMatchSeason;
use App\Models\ZqSubSclass;
use App\Models\ZqSchedule;
use Illuminate\Support\Facades\Log;


class SetDataController extends Controller
{
    /**
     * 联赛、杯赛入库
     * @return string
     */
    public function League_xml(){

        if (!Cache::store('file')->has('League_xml')){
            return "缓存里没有数据";
        }

        $data = Cache::store('file')->get('League_xml');

        \DB::transaction(function() use ($data){
            foreach ($data as $item){
                $country = FootBallCountry::updateOrCreate(
                    ['InfoID'=>$item['id'],'NameCN'=>$item['country']],
                    [
                        'NameFN'=>isset($item['NameFN']) ? $item['NameFN'] : "",
                        'NameEN'=>$item['countryEn'],
                        'FlagPic'=>$item['countryLogo'],
                        'Info_type'=>$item['areaID'],
                        'modifyTime'=>date('Y-m-d')
                    ]
                );

                foreach ($item['events'] as $event){
                    $zqSclass = ZqSclass::updateOrCreate(
                        ['SClassID'=>$event['id'],'Name_J' => $event['gb']],
                        [
                            'Name_F'=>$event['big'],
                            'Name_E' => $event['en'],
                            'Name_JS' => $event['gb_short'],
                            'Name_FS' => $event['big_short'],
                            'Name_ES' => $event['en_short'],
                            'Type' => $event['type'],
                            'Mode' => $event['type'],
                            'Total_Round' => $event['sum_round'],
                            'Curr_Round' => $event['curr_round'],
                            'Curr_matchSeason' => $event['Curr_matchSeason'],
                            'CountryID' => $country->InfoID,
                            'CountryName' => $country->NameCN,
                            'CountryEnName' => $country->NameEN,
                            'AreaID' => $event['areaID'],
                            'Logo' => $event['logo'],
                            'IsStop' => isset($event['IsStop'])?$event['IsStop']:0,
                            'Color' => $event['color'],
                            'SubSclass' => $event['SubSclass'],
                        ]
                    );
                    if ($zqSclass->Curr_matchSeason){
                        $ZqMatchSeason = ZqMatchSeason::updateOrCreate(
                            ['sclassId'=>$zqSclass->SClassID,'season'=>$zqSclass->Curr_matchSeason]
                        );
                    }
                }
            }
        });

        //return $data;
    }


    public function PlayerTech()
    {
        Log::useFiles(storage_path('logs/setData.log'));
        if (!Cache::store('file')->has('Play_Detail'))
        {
            Log::info("缓存里没有数据");
        }
        $data = Cache::store('file')->get('Play_Detail');
        try{
            \DB::transaction(function () use ($data) {
                foreach ($data as $val) {
                    foreach ($val as $item)
                    {
                        ZqPlayerTech::updateOrCreate(
                            ['PlayerID'=>$item['PlayerID'],'SClassID'=>$item['SClassID']],
                            [
                                'SClassID' => $item['SClassID'],  //赛季ID
                                'TeamID' => $item['TeamID'],   //球队ID
                                'PlayerID' => $item['PlayerID'], //球员ID
                                'Name_J' => $item['Name_J'], //球员名称简体
                                'Name_F' => $item['Name_F'], //球员名称繁体
                                'shots' => $item['shots'],      //射门次数
                                'shotsTarget' => $item['shotsTarget'], //射正次数
                                'wasFouled' => $item['wasFouled'],   //被犯规
                                'offside' => $item['offside'],  //越位
                                'totalPass' => $item['totalPass'], //传球总数
                                'passSuccess' => $item['accuratePass'], // 传球成功数
                                'keyPass' => $item['keyPass'], // 关键传球
                                'rating' => $item['rating'],  //评分
                                'longBalls' => $item['longBall'], //长传
                                'longBallsSuc' => $item['longBallWon'], //长传成功
                                'throughBall' => $item['throughBall'], //直塞
                                'throughBallSuc' => $item['throughBallWon'], //直塞成功
                                'Cross' => $item['CrossNum'],  //横传
                                'CrossSuc' => $item['CrossWon'],  //横传成功
                                'shotsBlocked' => $item['shotsBlocked'], //封堵
                                'fouls' => $item['fouls'], //犯规
                                'clearances' => $item['clearances'], //解围
                                'clearanceWon' => $item['clearanceWon'],//解围成功
                                'tackle' => $item['tackles'], //铲断
                                'dribblesSuc' => $item['dribblesWon'], //带球摆脱
                                'passSucPercent' => $item['PassRate'] //传球成功率
                            ]
                        );
                    }
                }
            });
            Log::info("球员技术统计数据写入数据库成功\n");
        }catch (\Exception $exception){
            Log::info("球员技术统计数据写入数据库失败：".$exception->getMessage());

        }
    }

    /**
     * 子联赛入库
     * @return string
     */
    public function SubLeague_XML(){
        if (!Cache::store('file')->has('SubLeague_XML')){
            return "缓存里没有数据";
        }

        $data = Cache::store('file')->get('SubLeague_XML');
        \DB::transaction(function () use ($data){
            foreach ($data as $item){
                $item = $item['data'];
                $sub = ZqSubSclass::updateOrCreate(
                    ['SubSclassID' => $item->SubSclassID],
                    [
                    'SclassID' => $item->SclassID,
                    'IsHaveScore' => $item->IsHaveScore,
                    'sortNumber' => $item->sortNumber,
                    'Curr_round' => $item->Curr_round,
                    'Count_round' => $item->Count_round,
                    'IsCurrentSclass' => $item->IsCurrentSclass,
                    'subSclassName' => $item->subSclassName,
                    'subName_JS' => isset($item->subName_JS) ? : "",
                    'SubName_Es' => isset($item->SubName_Es) ? : "",
                    'subName_Fs' => isset($item->subName_Fs) ? : "",
                    'includeSeason' => $item->includeSeason,
                    'IsZu' => $item->IsZu,
                    'MatchSeason' => $item->MatchSeason,
                ]);
            }
        });
    }


    public function lineup()
    {
        Log::useFiles(storage_path('logs/setData.log'));
        if (!Cache::store('file')->has('Zq_Lineup'))
        {
            Log::info("缓存里没有数据");
        }
        $data = Cache::store('file')->get('Zq_Lineup');
        try{
            \DB::transaction(function() use ($data){
                foreach ($data as $val){
                    ZqLineup::updateOrCreate(
                        ['ScheduleID'=>$val['ScheduleID']],
                        [
                            'ScheduleID' => $val['ScheduleID'],
                            'HomeLineup' => $val['HomeLineup'],
                            'awayLineup' => $val['awayLineup'],
                            'HomeLineupFirst' => $val['HomeLineupFirst'],
                            'AwayLineupFirst' => $val['AwayLineupFirst'],
                            'HomeBackup' => $val['HomeBackup'],
                            'AwayBackup' => $val['AwayBackup']
                        ]
                    );
                }
            });
            Log::info("比赛阵容数据写入数据库成功\n");
        }catch (\Exception $exception){
            Log::info("比赛阵容数据写入数据库失败：".$exception->getMessage());
        }
    }


    public function lqSClass()
    {
        Log::useFiles(storage_path('logs/setData.log'));
        if (!Cache::store('file')->has('SClass_list'))
        {
            Log::info("缓存里没有数据");
        }
        $data = Cache::store('file')->get('SClass_list');
        try{
            \DB::transaction(function() use ($data){
                foreach ($data as $val){
                    //国家数据
                    LqSclassInfo::updateOrCreate(
                        ['InfoID'=>$val['InfoID']],
                        [
                            'InfoID' => $val['InfoID'],
                            'NameCN' => $val['NameCN']
                        ]
                    );
                    //赛事数据
                    foreach ($val['SClass'] as $item)
                    {
                        LqSclass::updateOrCreate(
                            ['SClassID'=>$item['SClassID']],
                            [
                                'SClassID' => $item['SClassID'],
                                'Color' => $item['Color'],
                                'Name_JS' => $item['Name_JS'],
                                'Name_J' => $item['Name_J'],
                                'Name_F' => $item['Name_F'],
                                'Name_E' => $item['Name_E'],
                                'type' => $item['type'],
                                'Curr_matchSeason' => $item['Curr_matchSeason'],
                                'countryID' => $item['countryID'],
                                'country' => $item['country'],
                                'curr_year' => $item['curr_year'],
                                'curr_month' => $item['curr_month'],
                                'sclass_kind' => $item['sclass_kind'],
                                'sclass_time' => $item['sclass_time']
                            ]
                        );
                    }
                }
            });
            Log::info("篮球赛事数据写入数据库成功\n");
        }catch (\Exception $exception){
            Log::info("篮球赛事数据写入数据库失败：".$exception->getMessage());
        }
    }

    //赛事下比赛数据入库
    public function set_lqSchedule()
    {
        Log::useFiles(storage_path('logs/setData.log'));
        if (!Cache::store('file')->has('Schedule_list'))
        {
            Log::info("缓存里没有数据");
        }
        $data = Cache::store('file')->get('Schedule_list');
        try{
            \DB::transaction(function() use ($data) {
                foreach ($data as $item)
                {
                   LqSchedule::updateOrCreate(
                            ['ScheduleID'=>$item['ScheduleID'],'SClassID'=>$item['SClassID']],
                            [
                            'ScheduleID' =>intval($item['ScheduleID']),
                            'SClassID' =>intval($item['SClassID']),
                            'SClassType' =>intval($item['SClassType']),
                            'SClassName_J' =>$item['SClassName_J'],
                            'SClassName_F' =>$item['SClassName_F'],
                            'MatchNumber' =>intval($item['MatchNumber']),
                            'Color' =>$item['Color'],
                            'MatchTime' =>$item['MatchTime'],
                            'MatchState' =>intval($item['MatchState']),
                            'MatchNumberTime' =>$item['MatchNumberTime'],
                            'HomeTeamID' =>intval($item['HomeTeamID']),
                            'HomeTeamName_J' =>$item['HomeTeamName_J'],
                            'HomeTeamName_F' =>$item['HomeTeamName_F'],
                            'GuestTeamID' =>intval($item['GuestTeamID']),
                            'GuestTeamName_J'=>$item['GuestTeamName_J'],
                            'GuestTeamName_F' =>$item['GuestTeamName_F'],
                            'HomeTeamRank' =>intval($item['HomeTeamRank']),
                            'GuestTeamRank' =>intval($item['GuestTeamRank']),
                            'HomeTeamScore' =>intval($item['HomeTeamScore']),
                            'GuestTeamScore' =>intval($item['GuestTeamScore']),
                            'HomeOneScore' =>intval($item['HomeOneScore']),
                            'HomeTwoScore' =>intval($item['HomeTwoScore']),
                            'HomeThreeScore' =>intval($item['HomeThreeScore']),
                            'HomeFourScore' =>intval($item['HomeFourScore']),
                            'GuestOneScore' =>intval($item['GuestOneScore']),
                            'GuestTwoScore' =>intval($item['GuestTwoScore']),
                            'GuestThreeScore' =>intval($item['GuestThreeScore']),
                            'GuestFourScore' =>intval($item['GuestFourScore']),
                            'OverTimeNumber' =>intval($item['OverTimeNumber']),
                            'HomeOneOverTimeScore' =>intval($item['HomeOneOverTimeScore']),
                            'HomeTwoOverTimeScore'=>intval($item['HomeTwoOverTimeScore']),
                            'HomeThreeOverTimeScore' =>intval($item['HomeThreeOverTimeScore']),
                            'GuestOneOverTimeScore' =>intval($item['GuestOneOverTimeScore']),
                            'GuestTwoOverTimeScore' =>intval($item['GuestTwoOverTimeScore']),
                            'GuestThreeOverTimeScore' =>intval($item['GuestThreeOverTimeScore']),
                            'TechnicalStatistics' =>$item['TechnicalStatistics'],
                            'TVShow' =>$item['TVShow'],
                            'TVRemark' =>$item['TVRemark'],
                            'Neutral' =>intval($item['Neutral']),
                            'Season' =>$item['Season'],
                            'MatchType' =>$item['MatchType'],
                            'MatchAddress' =>$item['MatchAddress'],
                            'MatchCate' =>$item['MatchCate'],
                            'MatchSubSClass' =>$item['MatchSubSClass'],
                        ]
                    );
                }

            });
            Log::info("篮球比赛数据写入数据库成功\n");
        }catch (\Exception $exception){
            Log::info("篮球比赛数据写入数据库失败：".$exception->getMessage());
        }
    }

    public function SetLqSClass()
    {
        Log::useFiles(storage_path('logs/setData.log'));
        if (!Cache::store('file')->has('SClassInfo'))
        {
            Log::info("缓存里没有数据");
        }
        $data = Cache::store('file')->get('SClassInfo');
        try{
            \DB::transaction(function() use ($data){
                foreach ($data as $val){
                    //国家数据
                    LqSclassInfo::updateOrCreate(
                        ['InfoID'=>$val['countryID']],
                        [
                            'InfoID' => $val['countryID'],
                            'NameCN' => $val['countryName']
                        ]
                    );
                    //赛事数据
                    foreach ($val['SClass'] as $item)
                    {
                        LqSclass::updateOrCreate(
                            ['SClassID'=>$item['SClassID']],
                            [
                                'SClassID' => $item['SClassID'],
                                'Name_JS' => $item['Name_JS'],
                                'Name_J' => $item['Name_J'],
                                'countryID' => $val['countryID'],
                                'sclass_kind' => $item['sclass_kind'],
                                'All_matchSeason' => $item['All_matchSeason']
                            ]
                        );
                    }
                }
            });
            Log::info("篮球赛事数据写入数据库成功\n");
        }catch (\Exception $exception){
            Log::info("篮球赛事数据写入数据库失败：".$exception->getMessage());
        }
    }



    //今日比赛数据入库
    public function set_today_lqSchedule()
    {
        Log::useFiles(storage_path('logs/setData.log'));
        if (!Cache::store('file')->has('TodayScheduleList'))
        {
            Log::info("缓存里没有数据");
        }
        $data = Cache::store('file')->get('TodayScheduleList');
        try{
            \DB::transaction(function() use ($data) {
                foreach ($data as $item)
                {
                    LqSchedule::updateOrCreate(
                        ['ScheduleID'=>$item['ScheduleID'],'SClassID'=>$item['SClassID']],
                        [
                            'ScheduleID' =>intval($item['ScheduleID']),
                            'SClassID' =>intval($item['SClassID']),
                            'SClassType' =>$item['SClassType'],
                            'SClassName_J' =>$item['SClassName_J'],
                            'SClassName_F' =>$item['SClassName_F'],
                            'MatchNumber' =>$item['MatchNumber'],
                            'Color' =>$item['Color'],
                            'MatchTime' =>$item['MatchTime'],
                            'MatchState' =>$item['MatchState'],
                            'MatchNumberTime' =>$item['MatchNumberTime'],
                            'HomeTeamID' =>$item['HomeTeamID'],
                            'HomeTeamName_J' =>$item['HomeTeamName_J'],
                            'HomeTeamName_F' =>$item['HomeTeamName_F'],
                            'GuestTeamID' =>$item['GuestTeamID'],
                            'GuestTeamName_J'=>$item['GuestTeamName_J'],
                            'GuestTeamName_F' =>$item['GuestTeamName_F'],
                            'HomeTeamRank' =>$item['HomeTeamRank'],
                            'GuestTeamRank' =>$item['GuestTeamRank'],
                            'HomeTeamScore' =>$item['HomeTeamScore'],
                            'GuestTeamScore' =>$item['GuestTeamScore'],
                            'HomeOneScore' =>$item['HomeOneScore'],
                            'HomeTwoScore' =>$item['HomeTwoScore'],
                            'HomeThreeScore' =>$item['HomeThreeScore'],
                            'HomeFourScore' =>$item['HomeFourScore'],
                            'GuestOneScore' =>$item['GuestOneScore'],
                            'GuestTwoScore' =>$item['GuestTwoScore'],
                            'GuestThreeScore' =>$item['GuestThreeScore'],
                            'GuestFourScore' =>$item['GuestFourScore'],
                            'OverTimeNumber' =>$item['OverTimeNumber'],
                            'HomeOneOverTimeScore' =>$item['HomeOneOverTimeScore'],
                            'HomeTwoOverTimeScore'=>$item['HomeTwoOverTimeScore'],
                            'HomeThreeOverTimeScore' =>$item['HomeThreeOverTimeScore'],
                            'GuestOneOverTimeScore' =>$item['GuestOneOverTimeScore'],
                            'GuestTwoOverTimeScore' =>$item['GuestTwoOverTimeScore'],
                            'GuestThreeOverTimeScore' =>$item['GuestThreeOverTimeScore'],
                            'TechnicalStatistics' =>$item['TechnicalStatistics'],
                            'TVShow' =>$item['TVShow'],
                            'TVRemark' =>$item['TVRemark'],
                            'Neutral' =>$item['Neutral']
                        ]
                    );
                }
            });
            Log::info("今日篮球比赛数据写入数据库成功\n");
        }catch (\Exception $exception){
            Log::info("今日篮球比赛数据写入数据库失败：".$exception->getMessage());
        }
    }
}
