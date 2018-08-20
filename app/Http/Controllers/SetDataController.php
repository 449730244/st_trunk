<?php

namespace App\Http\Controllers;
set_time_limit(0);
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
        if (!Cache::store('file')->has('Play_Detail'))
        {
            echo "球员技术统计缓存里没有数据\n";
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
            echo "球员技术统计数据写入数据库成功\n";
        }catch (\Exception $exception){
            echo "球员技术统计数据写入数据库失败：".$exception->getMessage()."\n";

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
        if (!Cache::store('file')->has('Zq_Lineup'))
        {
            echo "比赛阵容缓存里没有数据\n";
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
            echo "比赛阵容数据写入数据库成功\n";
        }catch (\Exception $exception){
            echo "比赛阵容数据写入数据库失败：".$exception->getMessage()."\n";
        }
    }


    public function lqSClass()
    {
        if (!Cache::store('file')->has('SClass_list'))
        {
            echo "篮球赛事缓存里没有数据\n";
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
            echo "篮球赛事数据写入数据库成功\n";
        }catch (\Exception $exception){
            echo "篮球赛事数据写入数据库失败：".$exception->getMessage()."\n";
        }
    }

    //赛事下比赛数据入库
    public function set_lqSchedule()
    {
        if (!Cache::store('file')->has('Schedule_list'))
        {
            echo "篮球比赛缓存里没有数据\n";
        }
        $data = Cache::store('file')->get('Schedule_list');
        try{
            \DB::transaction(function() use ($data) {
                foreach ($data as $item)
                {
                    foreach($item as $val)
                    {

                        LqSchedule::updateOrCreate(
                            ['ScheduleID'=>$val['ScheduleID'],'SClassID'=>$val['SClassID']],
                            [
                                'ScheduleID' =>intval($val['ScheduleID']),
                                'SClassID' =>intval($val['SClassID']),
                                'SClassType' =>$val['SClassType'],
                                'SClassName_J' =>$val['SClassName_J'],
                                'SClassName_F' =>$val['SClassName_F'],
                                'MatchNumber' =>$val['MatchNumber'],
                                'Color' =>$val['Color'],
                                'MatchTime' =>$val['MatchTime'],
                                'MatchState' =>$val['MatchState'],
                                'MatchNumberTime' =>$val['MatchNumberTime'],
                                'HomeTeamID' =>$val['HomeTeamID'],
                                'HomeTeamName_J' =>$val['HomeTeamName_J'],
                                'HomeTeamName_F' =>$val['HomeTeamName_F'],
                                'GuestTeamID' =>$val['GuestTeamID'],
                                'GuestTeamName_J'=>$val['GuestTeamName_J'],
                                'GuestTeamName_F' =>$val['GuestTeamName_F'],
                                'HomeTeamRank' =>$val['HomeTeamRank'],
                                'GuestTeamRank' =>$val['GuestTeamRank'],
                                'HomeTeamScore' =>$val['HomeTeamScore'],
                                'GuestTeamScore' =>$val['GuestTeamScore'],
                                'HomeOneScore' =>$val['HomeOneScore'],
                                'HomeTwoScore' =>$val['HomeTwoScore'],
                                'HomeThreeScore' =>$val['HomeThreeScore'],
                                'HomeFourScore' =>$val['HomeFourScore'],
                                'GuestOneScore' =>$val['GuestOneScore'],
                                'GuestTwoScore' =>$val['GuestTwoScore'],
                                'GuestThreeScore' =>$val['GuestThreeScore'],
                                'GuestFourScore' =>$val['GuestFourScore'],
                                'OverTimeNumber' =>$val['OverTimeNumber'],
                                'HomeOneOverTimeScore' =>$val['HomeOneOverTimeScore'],
                                'HomeTwoOverTimeScore'=>$val['HomeTwoOverTimeScore'],
                                'HomeThreeOverTimeScore' =>$val['HomeThreeOverTimeScore'],
                                'GuestOneOverTimeScore' =>$val['GuestOneOverTimeScore'],
                                'GuestTwoOverTimeScore' =>$val['GuestTwoOverTimeScore'],
                                'GuestThreeOverTimeScore' =>$val['GuestThreeOverTimeScore'],
                                'TechnicalStatistics' =>$val['TechnicalStatistics'],
                                'TVShow' =>$val['TVShow'],
                                'TVRemark' =>$val['TVRemark'],
                                'Neutral' =>$val['Neutral'],
                                'Season' =>$val['Season'],
                                'MatchType' =>$val['MatchType'],
                                'MatchAddress' =>$val['MatchAddress'],
                                'MatchCate' =>$val['MatchCate'],
                                'MatchSubSClass' =>$val['MatchSubSClass'],
                            ]
                        );
                    }
                }

            });
            echo "篮球比赛数据写入数据库成功\n";
        }catch (\Exception $exception){
            echo "篮球比赛数据写入数据库失败：".$exception->getMessage();
        }
    }

    public function SetLqSClass()
    {
        if (!Cache::store('file')->has('SClassInfo'))
        {
            echo "新篮球赛事缓存里没有数据\n";
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
            echo "新篮球赛事数据写入数据库成功\n";
        }catch (\Exception $exception){
            echo "新篮球赛事数据写入数据库失败：".$exception->getMessage()."\n";
        }
    }



    //今日比赛数据入库
    public function set_today_lqSchedule()
    {
        if (!Cache::store('file')->has('TodayScheduleList'))
        {
            echo "今日比赛缓存里没有数据\n";
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
            echo "今日篮球比赛数据写入数据库成功\n";
        }catch (\Exception $exception){
            echo "今日篮球比赛数据写入数据库失败：".$exception->getMessage()."\n";
        }
    }
}
