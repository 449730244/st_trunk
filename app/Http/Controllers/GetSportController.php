<?php

namespace App\Http\Controllers;
set_time_limit(0);
use App\Models\FootBallCountry;
use App\Models\LqSclass;
use App\Models\LqSclassInfo;
use App\Models\ZqPlayerTech;
use App\Models\ZqSclass;
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

        //$content = self::curl('http://info.nowscore.com/jsData/Count/2018-2019/playerTech_1.js');
        //foreach (ZqSclass::orderBy('SClassID','asc')->cursor() as $k => $item)
        //{
         //   if(!empty(trim($item->Curr_matchSeason)))
           // {
                //echo 'http://info.nowscore.com/jsData/Count/'.trim($item->Curr_matchSeason).'/playerTech_'.$item->SClassID.'.js';
                //$content = self::curl('http://info.nowscore.com/jsData/Count/'.trim($item->Curr_matchSeason).'/playerTech_'.$item->SClassID.'.js');
                $content = self::curl('http://info.nowscore.com/jsData/Count/2018-2019/playerTech_36.js');
                if($content == strip_tags($content))
                {
                    //echo $item->SClassID."成功\n";
                    $newContent = str_replace('var techCout_Player=', '', $content);
                    $newContents = str_replace(';', '', $newContent);
                    $newData = str_replace('﻿', '', $newContents);
                    $playTech = json_decode($newData,true);
                    //dd($playTech['Total']);
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
                    $playerTech = [];
                    foreach ($playTech['Pid'] as $key => $val)
                    {
                        $Name_J = $val[0][0];
                        $Name_F = $val[0][1];
                        $TeamID = $val[1];
                        $PlayerID = $key;
                        //总统计
                        foreach ($total as $total_key => $val)
                        {
                            if($PlayerID == $val['PlayerID'])
                            {
                                $playerTech[$key]['total']['PlayerID'] = $PlayerID;
                                $playerTech[$key]['total']['Name_J'] = $Name_J;
                                $playerTech[$key]['total']['Name_F'] = $Name_F;
                                $playerTech[$key]['total']['TeamID'] = $TeamID;
                                $playerTech[$key]['total']['SchSum'] = $val['SchSum'];
                                $playerTech[$key]['total']['BackSum'] = $val['BackSum'];
                                $playerTech[$key]['total']['PlayingTime'] = $val['PlayingTime'];
                                $playerTech[$key]['total']['notPenaltyGoals'] = $val['notPenaltyGoals'];
                                $playerTech[$key]['total']['penaltyGoals'] = $val['penaltyGoals'];
                                $playerTech[$key]['total']['shots'] = $val['shots'];
                                $playerTech[$key]['total']['shotsTarget'] = $val['shotsTarget'];
                                $playerTech[$key]['total']['wasFouled'] = $val['wasFouled'];
                                $playerTech[$key]['total']['bestSum'] = $val['bestSum'];
                                $playerTech[$key]['total']['notPenaltyGoals'] = $val['notPenaltyGoals'];
                                $playerTech[$key]['total']['rating'] = $val['rating'];
                                $playerTech[$key]['total']['effRating'] = $val['effRating'];
                                $playerTech[$key]['total']['totalPass'] = $val['pass'];
                                $playerTech[$key]['total']['passSuccess'] = $val['passSuc'];
                                $playerTech[$key]['total']['keyPass'] = $val['keyPass'];
                                $playerTech[$key]['total']['assist'] = $val['assist'];
                                $playerTech[$key]['total']['longBalls'] = $val['longBalls'];
                                $playerTech[$key]['total']['longBallsSuc'] = $val['longBallsSuc'];
                                $playerTech[$key]['total']['throughBall'] = $val['throughBall'];
                                $playerTech[$key]['total']['throughBallSuc'] = $val['throughBallSuc'];
                                $playerTech[$key]['total']['Cross'] = $val['Cross'];
                                $playerTech[$key]['total']['CrossSuc'] = $val['CrossSuc'];
                                $playerTech[$key]['total']['dribblesSuc'] = $val['dribblesSuc'];
                                $playerTech[$key]['total']['offside'] = $val['offsideProvoked'];
                                $playerTech[$key]['total']['tackle'] = $val['tackle'];
                                $playerTech[$key]['total']['interception'] = $val['interception'];
                                $playerTech[$key]['total']['clearances'] = $val['clearance'];
                                $playerTech[$key]['total']['clearanceWon'] = $val['clearanceSuc'];
                                $playerTech[$key]['total']['dispossessed'] = $val['dispossessed'];
                                $playerTech[$key]['total']['shotsBlocked'] = $val['shotsBlocked'];
                                $playerTech[$key]['total']['aerialSuc'] = $val['aerialSuc'];
                                $playerTech[$key]['total']['fouls'] = $val['foul'];
                                $playerTech[$key]['total']['red'] = $val['red'];
                                $playerTech[$key]['total']['yellow'] = $val['yellow'];
                                $playerTech[$key]['total']['touch'] = $val['touch'];
                                $playerTech[$key]['total']['turnOver'] = $val['turnOver'];
                                $playerTech[$key]['total']['ownGoal'] = $val['ownGoal'];
                                $playerTech[$key]['total']['penaltyProvoked'] = $val['penaltyProvoked'];
                                $playerTech[$key]['total']['shotsOnPost'] = $val['shotsOnPost'];
                                $playerTech[$key]['total']['Goals'] = $val['Goals'];
                                $playerTech[$key]['total']['passSucPercent'] = $val['passSucPercent'];
                                $playerTech[$key]['total']['assistMinute'] = $val['assistMinute'];
                                $playerTech[$key]['total']['goalMinute'] = $val['goalMinute'];
                                $playerTech[$key]['total']['goalPercent'] = $val['goalPercent'];
                                $playerTech[$key]['total']['type'] = "total";
                            }
                        }
                        //主队统计
                        foreach ($home as $home_key => $val)
                        {
                            if($PlayerID == $val['PlayerID'])
                            {
                                $playerTech[$key]['home']['PlayerID'] = $PlayerID;
                                $playerTech[$key]['home']['Name_J'] = $Name_J;
                                $playerTech[$key]['home']['Name_F'] = $Name_F;
                                $playerTech[$key]['home']['TeamID'] = $TeamID;
                                $playerTech[$key]['home']['SchSum'] = $val['SchSum'];
                                $playerTech[$key]['home']['BackSum'] = $val['BackSum'];
                                $playerTech[$key]['home']['PlayingTime'] = $val['PlayingTime'];
                                $playerTech[$key]['home']['notPenaltyGoals'] = $val['notPenaltyGoals'];
                                $playerTech[$key]['home']['penaltyGoals'] = $val['penaltyGoals'];
                                $playerTech[$key]['home']['shots'] = $val['shots'];
                                $playerTech[$key]['home']['shotsTarget'] = $val['shotsTarget'];
                                $playerTech[$key]['home']['wasFouled'] = $val['wasFouled'];
                                $playerTech[$key]['home']['bestSum'] = $val['bestSum'];
                                $playerTech[$key]['home']['notPenaltyGoals'] = $val['notPenaltyGoals'];
                                $playerTech[$key]['home']['rating'] = $val['rating'];
                                $playerTech[$key]['home']['effRating'] = $val['effRating'];
                                $playerTech[$key]['home']['totalPass'] = $val['pass'];
                                $playerTech[$key]['home']['passSuccess'] = $val['passSuc'];
                                $playerTech[$key]['home']['keyPass'] = $val['keyPass'];
                                $playerTech[$key]['home']['assist'] = $val['assist'];
                                $playerTech[$key]['home']['longBalls'] = $val['longBalls'];
                                $playerTech[$key]['home']['longBallsSuc'] = $val['longBallsSuc'];
                                $playerTech[$key]['home']['throughBall'] = $val['throughBall'];
                                $playerTech[$key]['home']['throughBallSuc'] = $val['throughBallSuc'];
                                $playerTech[$key]['home']['Cross'] = $val['Cross'];
                                $playerTech[$key]['home']['CrossSuc'] = $val['CrossSuc'];
                                $playerTech[$key]['home']['dribblesSuc'] = $val['dribblesSuc'];
                                $playerTech[$key]['home']['offside'] = $val['offsideProvoked'];
                                $playerTech[$key]['home']['tackle'] = $val['tackle'];
                                $playerTech[$key]['home']['interception'] = $val['interception'];
                                $playerTech[$key]['home']['clearances'] = $val['clearance'];
                                $playerTech[$key]['home']['clearanceWon'] = $val['clearanceSuc'];
                                $playerTech[$key]['home']['dispossessed'] = $val['dispossessed'];
                                $playerTech[$key]['home']['shotsBlocked'] = $val['shotsBlocked'];
                                $playerTech[$key]['home']['aerialSuc'] = $val['aerialSuc'];
                                $playerTech[$key]['home']['fouls'] = $val['foul'];
                                $playerTech[$key]['home']['red'] = $val['red'];
                                $playerTech[$key]['home']['yellow'] = $val['yellow'];
                                $playerTech[$key]['home']['touch'] = $val['touch'];
                                $playerTech[$key]['home']['turnOver'] = $val['turnOver'];
                                $playerTech[$key]['home']['ownGoal'] = $val['ownGoal'];
                                $playerTech[$key]['home']['penaltyProvoked'] = $val['penaltyProvoked'];
                                $playerTech[$key]['home']['shotsOnPost'] = $val['shotsOnPost'];
                                $playerTech[$key]['home']['Goals'] = $val['Goals'];
                                $playerTech[$key]['home']['passSucPercent'] = $val['passSucPercent'];
                                $playerTech[$key]['home']['assistMinute'] = $val['assistMinute'];
                                $playerTech[$key]['home']['goalMinute'] = $val['goalMinute'];
                                $playerTech[$key]['home']['goalPercent'] = $val['goalPercent'];
                                $playerTech[$key]['home']['type'] = "home";
                            }
                        }
                        //客队统计
                        foreach ($guest as $guest_key => $val)
                        {
                            if($PlayerID == $val['PlayerID'])
                            {
                                $playerTech[$key]['guest']['PlayerID'] = $PlayerID;
                                $playerTech[$key]['guest']['Name_J'] = $Name_J;
                                $playerTech[$key]['guest']['Name_F'] = $Name_F;
                                $playerTech[$key]['guest']['TeamID'] = $TeamID;
                                $playerTech[$key]['guest']['SchSum'] = $val['SchSum'];
                                $playerTech[$key]['guest']['BackSum'] = $val['BackSum'];
                                $playerTech[$key]['guest']['PlayingTime'] = $val['PlayingTime'];
                                $playerTech[$key]['guest']['notPenaltyGoals'] = $val['notPenaltyGoals'];
                                $playerTech[$key]['guest']['penaltyGoals'] = $val['penaltyGoals'];
                                $playerTech[$key]['guest']['shots'] = $val['shots'];
                                $playerTech[$key]['guest']['shotsTarget'] = $val['shotsTarget'];
                                $playerTech[$key]['guest']['wasFouled'] = $val['wasFouled'];
                                $playerTech[$key]['guest']['bestSum'] = $val['bestSum'];
                                $playerTech[$key]['guest']['notPenaltyGoals'] = $val['notPenaltyGoals'];
                                $playerTech[$key]['guest']['rating'] = $val['rating'];
                                $playerTech[$key]['guest']['effRating'] = $val['effRating'];
                                $playerTech[$key]['guest']['totalPass'] = $val['pass'];
                                $playerTech[$key]['guest']['passSuccess'] = $val['passSuc'];
                                $playerTech[$key]['guest']['keyPass'] = $val['keyPass'];
                                $playerTech[$key]['guest']['assist'] = $val['assist'];
                                $playerTech[$key]['guest']['longBalls'] = $val['longBalls'];
                                $playerTech[$key]['guest']['longBallsSuc'] = $val['longBallsSuc'];
                                $playerTech[$key]['guest']['throughBall'] = $val['throughBall'];
                                $playerTech[$key]['guest']['throughBallSuc'] = $val['throughBallSuc'];
                                $playerTech[$key]['guest']['Cross'] = $val['Cross'];
                                $playerTech[$key]['guest']['CrossSuc'] = $val['CrossSuc'];
                                $playerTech[$key]['guest']['dribblesSuc'] = $val['dribblesSuc'];
                                $playerTech[$key]['guest']['offside'] = $val['offsideProvoked'];
                                $playerTech[$key]['guest']['tackle'] = $val['tackle'];
                                $playerTech[$key]['guest']['interception'] = $val['interception'];
                                $playerTech[$key]['guest']['clearances'] = $val['clearance'];
                                $playerTech[$key]['guest']['clearanceWon'] = $val['clearanceSuc'];
                                $playerTech[$key]['guest']['dispossessed'] = $val['dispossessed'];
                                $playerTech[$key]['guest']['shotsBlocked'] = $val['shotsBlocked'];
                                $playerTech[$key]['guest']['aerialSuc'] = $val['aerialSuc'];
                                $playerTech[$key]['guest']['fouls'] = $val['foul'];
                                $playerTech[$key]['guest']['red'] = $val['red'];
                                $playerTech[$key]['guest']['yellow'] = $val['yellow'];
                                $playerTech[$key]['guest']['touch'] = $val['touch'];
                                $playerTech[$key]['guest']['turnOver'] = $val['turnOver'];
                                $playerTech[$key]['guest']['ownGoal'] = $val['ownGoal'];
                                $playerTech[$key]['guest']['penaltyProvoked'] = $val['penaltyProvoked'];
                                $playerTech[$key]['guest']['shotsOnPost'] = $val['shotsOnPost'];
                                $playerTech[$key]['guest']['Goals'] = $val['Goals'];
                                $playerTech[$key]['guest']['passSucPercent'] = $val['passSucPercent'];
                                $playerTech[$key]['guest']['assistMinute'] = $val['assistMinute'];
                                $playerTech[$key]['guest']['goalMinute'] = $val['goalMinute'];
                                $playerTech[$key]['guest']['goalPercent'] = $val['goalPercent'];
                                $playerTech[$key]['guest']['type'] = "guest";
                            }
                        }
                    }
                    foreach ($playerTech as $val)
                    {

                    }
                }else{
                    echo "沒有数据\n";
                }
           // }
            //sleep(15);
       // }

    }
}
