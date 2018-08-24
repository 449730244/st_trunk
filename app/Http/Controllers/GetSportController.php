<?php

namespace App\Http\Controllers;
set_time_limit(0);
use App\Models\FootBallCountry;
use App\Models\LqSclass;
use App\Models\LqSclassInfo;
use App\Models\ZqPlayerTech;
use App\Models\ZqSclass;
use App\Models\ZqSubSclass;
use Illuminate\Http\Request;

class GetSportController extends Controller
{

    //获取首页数据
    public function getSportHome()
    {
        $content = self::curl('http://info.nowscore.com/jsData/infoHeader.js');
        $string = str_replace('var arr=new Array();', '', $content);
        $newString = str_replace('arr', '$sportData', $string);
        $newContent = '<?php '.$newString;
        $newContents = str_replace('﻿', '', $newContent);
        if($ZqHomeInfo = fopen(storage_path("tmp/ZqHomeInfo.php"),'w'))
        {
            fwrite($ZqHomeInfo,$newContents);
            fclose($ZqHomeInfo);
        }
        $sportData =[];
        include_once storage_path("tmp/ZqHomeInfo.php");
        foreach ($sportData as $k => $item)
        {
            //$InfoID = substr($item[0],7);
            FootBallCountry::updateOrCreate(
                ['InfoID'=> substr($item[0],7)],
                [
                    'InfoID' =>substr($item[0],7),
                    'NameCN' =>$item[1]
                ]
            );
            foreach ($item[4] as $key => $val)
            {
                $sclass = explode(',',$val);
                $SClassID = $sclass[0];
                $SClassName = $sclass[1];
                /*unset($sclass[0]);
                unset($sclass[1]);
                unset($sclass[2]);
                unset($sclass[3]);
                $season = implode(',',$sclass);*/
                ZqSclass::updateOrCreate(
                    ['SClassID' => $SClassID],
                    ['Name_JS'  => $SClassName]
                );
            }
        }
    }

    public function getLeftData()
    {
        $content = self::curl('http://info.nowscore.com/jsData/leftData/leftData.js');
        $string = str_replace('var arrArea = new Array();', '', $content);
        $newString = str_replace('arrArea', '$sportData', $string);
        $newContent = '<?php '.$newString;
        $newContents = str_replace('﻿', '', $newContent);
        if($ZqHomeInfo = fopen(storage_path("tmp/ZqLeftData.php"),'w'))
        {
            fwrite($ZqHomeInfo,$newContents);
            fclose($ZqHomeInfo);
        }
        $sportData =[];
        include_once storage_path("tmp/ZqLeftData.php");
        foreach($sportData as $item)
        {
            foreach ($item as $v)
            {
                $newArr = array_merge($v[4],$v[5]);
                foreach ($newArr as $val)
                {
                    ZqSclass::updateOrCreate(
                        ['SClassID' => $val[0]],
                        [
                            'SClassID' =>$val[0],
                            'Name_JS' =>$val[0],
                            'Name_FS' =>$val[0],
                            'Name_ES' =>$val[0],
                            'AreaID'  => $v[3]
                        ]
                    );
                }
            }
        }
    }

    public function getLqSportHome()
    {
        $content = self::curl('http://nba.nowscore.com/jsData/infoHeader_cn.js');
        $string = str_replace('var arr=new Array();', '', $content);
        $newString = str_replace('arr', '$sportData', $string);
        $newContent = '<?php '.$newString;
        $newContents = str_replace('﻿', '', $newContent);
        if($LqHomeInfo = fopen(storage_path("tmp/LqHomeInfo.php"),'w'))
        {
            fwrite($LqHomeInfo,$newContents);
            fclose($LqHomeInfo);
        }
        $sportData =[];
        include_once storage_path("tmp/LqHomeInfo.php");
        foreach ($sportData as $k => $item)
        {
            //$InfoID = substr($item[0],7);
            LqSclassInfo::updateOrCreate(
                ['InfoID'=> substr($item[0],7)],
                [
                    'InfoID' =>substr($item[0],7),
                    'NameCN' =>$item[1],
                    'FlagPic' => "http://nba.nowscore.com".$item[2]
                ]
            );
            foreach ($item[4] as $key => $val)
            {
                $sclass = explode(',',$val);
                $SClassID = $sclass[0];
                $SClassName = $sclass[1];
                /*unset($sclass[0]);
                unset($sclass[1]);
                unset($sclass[2]);
                unset($sclass[3]);
                $season = implode(',',$sclass);*/
                LqSclass::updateOrCreate(
                    ['SClassID' => $SClassID],
                    ['Name_JS'  => $SClassName]
                );
            }
        }
    }

    public  function getZqPlayerTech()
    {
        foreach (ZqSclass::orderBy('SClassID','asc')->cursor() as $k => $item)
        {
            if(!empty(trim($item->Curr_matchSeason)))
            {
                $content = self::curl('http://info.nowscore.com/jsData/Count/'.trim($item->Curr_matchSeason).'/playerTech_'.$item->SClassID.'.js');
                //$content = self::curl('http://info.nowscore.com/jsData/Count/2018-2019/playerTech_36.js');
                if($content == strip_tags($content))
                {
                    //echo "获取赛事".$item->SClassID."数据成功\n";
                    $newContent = str_replace('var techCout_Player=', '', $content);
                    $newContents = str_replace(';', '', $newContent);
                    $newData = str_replace('﻿', '', $newContents);
                    $playTech = json_decode($newData,true);
                    //dd($playTech['Total']);
                    if(!empty($playTech['Total']['value']))
                    {
                        foreach($playTech['Total']['value'] as $key =>$val)
                        {
                            $total[] = array_combine(array_keys($playTech['Total']['key']),$val);
                        }
                        foreach($playTech['Home']['value'] as $key =>$val)
                        {
                            $home[] = array_combine(array_keys($playTech['Home']['key']),$val);
                        }
                        foreach($playTech['guest']['value'] as $key =>$val)
                        {
                            $guest[] = array_combine(array_keys($playTech['guest']['key']),$val);
                        }
                        foreach ($playTech['Pid'] as $key => $val)
                        {
                            $Name_J = $val[0][0];
                            $Name_F = $val[0][1];
                            $TeamID = $val[1];
                            $PlayerID = $key;
                            //总统计
                            foreach ($total as $total_key => $total_val)
                            {
                                if($PlayerID == $total_val['PlayerID'])
                                {
                                    try{
                                        ZqPlayerTech::updateOrCreate(
                                            ['PlayerID'=>$PlayerID,'type'=>'total'],
                                            [
                                                'PlayerID' => $PlayerID,
                                                'Name_J' => $Name_J,
                                                'Name_F' => $Name_F,
                                                'TeamID' => $TeamID,
                                                'SClassID' => 36,
                                                'SchSum' => $total_val['SchSum'],
                                                'BackSum' => $total_val['BackSum'],
                                                'PlayingTime' => $total_val['PlayingTime'],
                                                'notPenaltyGoals' => $total_val['notPenaltyGoals'],
                                                'penaltyGoals' => $total_val['penaltyGoals'],
                                                'shots' => $total_val['shots'],
                                                'shotsTarget' => $total_val['shotsTarget'],
                                                'wasFouled' => $total_val['wasFouled'],
                                                'bestSum' => $total_val['bestSum'],
                                                'notPenaltyGoals' => $total_val['notPenaltyGoals'],
                                                'rating' => $total_val['rating'],
                                                'effRating' => $total_val['effRating'],
                                                'totalPass' => $total_val['pass'],
                                                'passSuccess' => $total_val['passSuc'],
                                                'keyPass' => $total_val['keyPass'],
                                                'assist' => $total_val['assist'],
                                                'longBalls' => $total_val['longBalls'],
                                                'longBallsSuc' => $total_val['longBallsSuc'],
                                                'throughBall' => $total_val['throughBall'],
                                                'throughBallSuc' => $total_val['throughBallSuc'],
                                                'Cross' => $total_val['Cross'],
                                                'CrossSuc' => $total_val['CrossSuc'],
                                                'dribblesSuc' => $total_val['dribblesSuc'],
                                                'offside' => $total_val['offsideProvoked'],
                                                'tackle' => $total_val['tackle'],
                                                'interception' => $total_val['interception'],
                                                'clearances' => $total_val['clearance'],
                                                'clearanceWon' => $total_val['clearanceSuc'],
                                                'dispossessed' => $total_val['dispossessed'],
                                                'shotsBlocked' => $total_val['shotsBlocked'],
                                                'aerialSuc' => $total_val['aerialSuc'],
                                                'fouls' => $total_val['foul'],
                                                'red' => $total_val['red'],
                                                'yellow' => $total_val['yellow'],
                                                'touch' => $total_val['touch'],
                                                'turnOver' => $total_val['turnOver'],
                                                'ownGoal' => $total_val['ownGoal'],
                                                'penaltyProvoked' => $total_val['penaltyProvoked'],
                                                'shotsOnPost' => $total_val['shotsOnPost'],
                                                'Goals' => $total_val['Goals'],
                                                'passSucPercent' => $total_val['passSucPercent'],
                                                'assistMinute' => $total_val['assistMinute'],
                                                'goalMinute' => $total_val['goalMinute'],
                                                'goalPercent' => $total_val['goalPercent'],
                                                'type' => 'total'
                                            ]
                                        );
                                        echo "球员".$PlayerID."总统计数据写入成功\n";
                                    }catch (\Exception $exception){
                                        echo "球员".$PlayerID."总统计数据写入失败".$exception->getMessage()."\n";
                                    }
                                }
                            }
                            //主队统计
                            foreach ($home as $home_key => $home_val)
                            {
                                if($PlayerID == $home_val['PlayerID'])
                                {
                                    try{
                                        ZqPlayerTech::updateOrCreate(
                                            ['PlayerID'=>$PlayerID,'type'=>'home'],
                                            [
                                                'PlayerID' => $PlayerID,
                                                'Name_J' => $Name_J,
                                                'Name_F' => $Name_F,
                                                'TeamID' => $TeamID,
                                                'SClassID' => $item->SClassID,
                                                'SchSum' => $home_val['SchSum'],
                                                'BackSum' => $home_val['BackSum'],
                                                'PlayingTime' => $home_val['PlayingTime'],
                                                'notPenaltyGoals' => $home_val['notPenaltyGoals'],
                                                'penaltyGoals' => $home_val['penaltyGoals'],
                                                'shots' => $home_val['shots'],
                                                'shotsTarget' => $home_val['shotsTarget'],
                                                'wasFouled' => $home_val['wasFouled'],
                                                'bestSum' => $home_val['bestSum'],
                                                'notPenaltyGoals' => $home_val['notPenaltyGoals'],
                                                'rating' => $home_val['rating'],
                                                'effRating' => $home_val['effRating'],
                                                'totalPass' => $home_val['pass'],
                                                'passSuccess' => $home_val['passSuc'],
                                                'keyPass' => $home_val['keyPass'],
                                                'assist' => $home_val['assist'],
                                                'longBalls' => $home_val['longBalls'],
                                                'longBallsSuc' => $home_val['longBallsSuc'],
                                                'throughBall' => $home_val['throughBall'],
                                                'throughBallSuc' => $home_val['throughBallSuc'],
                                                'Cross' => $home_val['Cross'],
                                                'CrossSuc' => $home_val['CrossSuc'],
                                                'dribblesSuc' => $home_val['dribblesSuc'],
                                                'offside' => $home_val['offsideProvoked'],
                                                'tackle' => $home_val['tackle'],
                                                'interception' => $home_val['interception'],
                                                'clearances' => $home_val['clearance'],
                                                'clearanceWon' => $home_val['clearanceSuc'],
                                                'dispossessed' => $home_val['dispossessed'],
                                                'shotsBlocked' => $home_val['shotsBlocked'],
                                                'aerialSuc' => $home_val['aerialSuc'],
                                                'fouls' => $home_val['foul'],
                                                'red' => $home_val['red'],
                                                'yellow' => $home_val['yellow'],
                                                'touch' => $home_val['touch'],
                                                'turnOver' => $home_val['turnOver'],
                                                'ownGoal' => $home_val['ownGoal'],
                                                'penaltyProvoked' => $home_val['penaltyProvoked'],
                                                'shotsOnPost' => $home_val['shotsOnPost'],
                                                'Goals' => $home_val['Goals'],
                                                'passSucPercent' => $home_val['passSucPercent'],
                                                'assistMinute' => $home_val['assistMinute'],
                                                'goalMinute' => $home_val['goalMinute'],
                                                'goalPercent' => $home_val['goalPercent'],
                                                'type' => 'home'
                                            ]
                                        );
                                        echo "球员".$PlayerID."主队数据统计写入成功\n";
                                    }catch (\Exception $exception){
                                        echo "球员".$PlayerID."主队数据统计写入失败".$exception->getMessage()."\n";
                                    }
                                }
                            }
                            //客队统计
                            foreach ($guest as $guest_key => $guest_val)
                            {
                                if($PlayerID == $guest_val['PlayerID'])
                                {
                                    try{
                                        ZqPlayerTech::updateOrCreate(
                                            ['PlayerID'=>$PlayerID,'type'=>'guest'],
                                            [
                                                'PlayerID' => $PlayerID,
                                                'Name_J' => $Name_J,
                                                'Name_F' => $Name_F,
                                                'TeamID' => $TeamID,
                                                'SClassID' => $item->SClassID,
                                                'SchSum' => $guest_val['SchSum'],
                                                'BackSum' => $guest_val['BackSum'],
                                                'PlayingTime' => $guest_val['PlayingTime'],
                                                'notPenaltyGoals' => $guest_val['notPenaltyGoals'],
                                                'penaltyGoals' => $guest_val['penaltyGoals'],
                                                'shots' => $guest_val['shots'],
                                                'shotsTarget' => $guest_val['shotsTarget'],
                                                'wasFouled' => $guest_val['wasFouled'],
                                                'bestSum' => $guest_val['bestSum'],
                                                'notPenaltyGoals' => $guest_val['notPenaltyGoals'],
                                                'rating' => $guest_val['rating'],
                                                'effRating' => $guest_val['effRating'],
                                                'totalPass' => $guest_val['pass'],
                                                'passSuccess' => $guest_val['passSuc'],
                                                'keyPass' => $guest_val['keyPass'],
                                                'assist' => $guest_val['assist'],
                                                'longBalls' => $guest_val['longBalls'],
                                                'longBallsSuc' => $guest_val['longBallsSuc'],
                                                'throughBall' => $guest_val['throughBall'],
                                                'throughBallSuc' => $guest_val['throughBallSuc'],
                                                'Cross' => $guest_val['Cross'],
                                                'CrossSuc' => $guest_val['CrossSuc'],
                                                'dribblesSuc' => $guest_val['dribblesSuc'],
                                                'offside' => $guest_val['offsideProvoked'],
                                                'tackle' => $guest_val['tackle'],
                                                'interception' => $guest_val['interception'],
                                                'clearances' => $guest_val['clearance'],
                                                'clearanceWon' => $guest_val['clearanceSuc'],
                                                'dispossessed' => $guest_val['dispossessed'],
                                                'shotsBlocked' => $guest_val['shotsBlocked'],
                                                'aerialSuc' => $guest_val['aerialSuc'],
                                                'fouls' => $guest_val['foul'],
                                                'red' => $guest_val['red'],
                                                'yellow' => $guest_val['yellow'],
                                                'touch' => $guest_val['touch'],
                                                'turnOver' => $guest_val['turnOver'],
                                                'ownGoal' => $guest_val['ownGoal'],
                                                'penaltyProvoked' => $guest_val['penaltyProvoked'],
                                                'shotsOnPost' => $guest_val['shotsOnPost'],
                                                'Goals' => $guest_val['Goals'],
                                                'passSucPercent' => $guest_val['passSucPercent'],
                                                'assistMinute' => $guest_val['assistMinute'],
                                                'goalMinute' => $guest_val['goalMinute'],
                                                'goalPercent' => $guest_val['goalPercent'],
                                                'type' => 'guest'
                                            ]
                                        );
                                        echo "球员".$PlayerID."客队数据统计写入成功\n";
                                    }catch (\Exception $exception){
                                        echo "球员".$PlayerID."客队数据统计写入失败".$exception->getMessage()."\n";
                                    }
                                }
                            }
                        }
                    }else{
                        echo "获取赛事".$item->SClassID."数据为空\n";
                    }
                }else{
                    echo $item->SClassID."沒有数据\n";
                }
            }
            sleep(15);
        }
    }
}
