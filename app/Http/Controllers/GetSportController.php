<?php

namespace App\Http\Controllers;

use App\Models\FootBallCountry;
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
}
