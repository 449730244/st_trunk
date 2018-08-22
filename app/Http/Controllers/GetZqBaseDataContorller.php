<?php

namespace App\Http\Controllers;

set_time_limit(0);

use App\Models\FootBallCountry;
use App\Models\FootBallPlayer;
use App\Models\FootBallTeam;
use App\Models\ZqSclass;
use App\Models\ZqScore;
use App\Models\ZqSubSclass;
use App\Models\ZqTopScorer;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GetZqBaseDataContorller extends Controller
{

    /**
     * 国家列表
     */
    public function getCountryInfo()
    {

        $data = json_decode($this->get('League_XML.aspx'), true);

        if ($data["status"] == 1) {

            foreach ($data["data"]["match"] as $v) {
                $newdata["InfoID"] = $v["countryID"];//国家id
                $newdata["NameCN"] = $v["country"];//国家简体名
                $newdata["NameFN"] = '';//国家繁体名
                $newdata["NameEN"] = $v["countryEn"];//国家英文名
                $newdata["FlagPic"] = !is_array($v["countryLogo"]) ? $v["countryLogo"] : "";//国家队图标
                $newdata["infoOrder"] = 0;//国家队的排序
                $newdata["Info_type"] = $v["areaID"];//所在的地区 1欧洲2美洲3亚洲4大洋洲5非洲
                $newdata["modifyTime"] = date("Y-m-d H:i:s");//修改时间
                $newdata["allOrder"] = $v["countryID"];//国家排序

                $insert = FootBallCountry::updateOrCreate($newdata["InfoID"] = $v["countryID"], $newdata);

            }
            return "success";

        }

    }

    /**
     * 获取球员信息
     * @param array $param
     */
    public function getFootBallPlayerInfo($param="",$mode="teamid")
    {


        $response = $mode=="teamid"?$this->get('Player_XML.aspx', "teamID=$param"):$this->get('Player_XML.aspx', "day=$param");
        // $response = $this->get('Player_XML.aspx', "day=$param");
        //参数：1、day:返回"day"天内更新过的球员数据2、teamID:返回该球队所有球员数据
        $ret = json_decode($response);
        if (is_object($ret)&&$ret->status == 1&&!empty($ret->data)) {
            $result = is_array($ret->data->i) ? $ret->data->i : [$ret->data->i];
            foreach ($result as $info) {

                $feet["左脚"] = 0;
                $feet["右脚"] = 1;
                $feet["双脚"] = 2;
                $Feet = $this->checkEmpty($info->feet);
                $data = [
                    'PlayerID' => $info->PlayerID,
//                        'Kind'=>$info->Kind,
//                        'Name_Short'=>$info->Name_J,
                    'Name_F' => $this->checkEmpty($info->Name_F),
                    'Name_J' => $this->checkEmpty($info->Name_J),
                    'Name_E' => $this->checkEmpty($info->Name_E),
//                        'Name_Es'=>'',
                    'Birthday' => $this->checkEmpty($info->Birthday),
                    'Tallness' => $this->checkEmpty($info->Tallness),
                    'Weight' => (int)$this->checkEmpty($info->Weight),
                    'Country' => $this->checkEmpty($info->Country),
                    'Photo' => $this->checkEmpty($info->Photo),
                    'Introduce' => $this->checkEmpty($info->Introduce),
                    'Health' => $this->checkEmpty($info->Health),
                    'IdiomaticFeet' => (int)($Feet && $feet[$Feet] ? $feet[$Feet] : 9),//惯用脚 0：左脚 1：右脚 2：双脚 3:未知
                    'ExpectedValue' => $this->checkEmpty($info->value),
//                        'HonorInfo'=>$info->HonorInfo,
                    'ModifyTime' => date("Y-m-d H:i:s"),
                    'TeamID' => !empty($this->checkEmpty($info->TeamID))?(int)$this->checkEmpty($info->TeamID):$mode=="teamid"?$param:"" ,
                    'Place' => $this->checkEmpty($info->Place),
                ];


                FootBallPlayer::updateOrCreate(['PlayerID' => $info->PlayerID], $data);

            }
            return "success";
        }
    }

    /**
     * 获取球队信息
     */
    public function getFootBallTeamInfo()
    {

        $response = $this->get('Team_XML.aspx');
        $ret = json_decode($response);
        if (!empty($ret) || is_object($ret))

            foreach ($ret->data as $v) {
                foreach ($v as $info) {
                    $data = [
                        'TeamID' => (int)$info->id,
                        'SclassID' => (int)$this->checkEmpty($info->lsID),
                        /*                    'Name_Short' => "",*/
                        'Name_J' => $this->checkEmpty($info->g),
                        'Name_F' => $this->checkEmpty($info->b),
                        'Name_E' => $this->checkEmpty($info->e),
                        'Found_date' => $this->checkEmpty($info->Found),
                        'Area' => $this->checkEmpty($info->Area),
                        'Gymnasium' => $this->checkEmpty($info->gym),
                        'Capacity' => $this->checkEmpty($info->Capacity),
                        'Flag' => $this->checkEmpty($info->Flag),
                        'Address' => $this->checkEmpty($info->addr),
                        'URL' => $this->checkEmpty($info->URL),
//                        'Introduce' => "",
                        'Drillmaster' => $this->checkEmpty($info->master),
//                        'MasterPic' => "",
//                        'MasterIntro' => "",
//                        'HomePoloShirt' => "",
//                        'GuestPoloShirt' => "",
                    ];
                    FootBallTeam::updateOrCreate(['TeamID' => (int)$this->checkEmpty($info->id)], $data);

                }

            }
        return "success";
    }

    /**
     * 积分统计
     */
    public function integralStatistic($id = '')
    {

        $arrid   =   explode("&subID=",$id);
        $SClassID=$arrid[0];
        $subID=null;
        (count($arrid)>1)?$subID=$arrid[1]:null;

        $response = $this->get('jifen.aspx', "ID=" . $id);
//        $response = $this->get('jifen.aspx', "ID=61");
        $status = json_decode($response)->status;
        if ($status == 2) {
            $reslult = json_decode($response);

            $res = base64_decode($reslult->data);
            $php_header = '<?php' . PHP_EOL;
            $res = preg_replace("/var\s*/", " $", $res);
            file_put_contents(storage_path('data/jsdata.php'), $php_header . $res);
            $arrTeam = [];
            $scoreColor = [];
            $totalScore = [];
            $homeScore = [];
            $guestScore = [];
            $halfScore = [];
            $homeHalfScore = [];
            $guestHalfScore = [];
            $Season = '';
            (include(storage_path('data/jsdata.php')));
            $newdata = [
                "status" => 2,
                "data" => [
//                    "arrTeam" => $arrTeam,
//                    "scoreColor" => $scoreColor,
                    "totalScore" => $totalScore,
                    "homeScore" => $homeScore,
                    "guestScore" => $guestScore,
                    "halfScore" => $halfScore,
                    "homeHalfScore" => $homeHalfScore,
                    "guestHalfScore" => $guestHalfScore,
//                    "Season" => $Season,
                ]
            ];
            // var_dump($newdata);
            foreach ($newdata["data"] as $k => $d) {
                $type = 0;
                switch ($k) {
                    case "totalScore":
                        $type = 1;
                        break;
                    case "homeScore":
                        $type = 2;
                        break;
                    case "guestScore":
                        $type = 3;
                        break;
                    case "halfScore":
                        $type = 4;
                        break;
                    case "homeHalfScore":
                        $type = 5;
                        break;
                    case "guestHalfScore":
                        $type = 6;
                        break;
                }
                if ($type < 1) continue;
                foreach ($d as $v) {
                    $data = [];
                    if ($type == 1) {

                        $data["Ranking"] = $v[0];
                        $data["Color"] = $v[0] >= 0 ? $scoreColor[$v[0]] : null;

                        $data["Red_carded"] = $v[3];

                        $data["Deduct_Score"] = $v[17];
                        $data["Deduct_Score_explain"] = $v[18];

                        $data["TeamID"] = $teamid = $v[2];
                        $data["Sequence"] = $v[1];//排名
                        $data["Match_total"] = $v[4];
                        $data["Win"] = $v[5];
                        $data["Draw"] = $v[6];
                        $data["Lose"] = $v[7];
                        $data["A"] = $v[8];
                        $data["L"] = $v[9];
                        $data["GD"] = $v[10];
                        $data["Winning_rate"] = $v[11];
                        $data["Equality_rate"] = $v[12];
                        $data["Negative_rate"] = $v[13];
                        $data["A_average"] = $v[14];
                        $data["L_average"] = $v[15];
                        $data["Total_Score"] = $v[16];
                        $data["Match1"] = $v[19];
                        $data["Match2"] = $v[20];
                        $data["Match3"] = $v[21];
                        $data["Match4"] = $v[22];
                        $data["Match5"] = $v[23];
                        $data["Match6"] = $v[24];

                    } else {
                        $data["Sequence"] = $v[0];
                        $data["TeamID"] = $teamid = $v[1];
                        $data["Match_total"] = $v[2];
                        $data["Win"] = $v[3];
                        $data["Draw"] = $v[4];
                        $data["Lose"] = $v[5];
                        $data["A"] = $v[6];
                        $data["L"] = $v[7];
                        $data["GD"] = $v[8];
                        $data["Winning_rate"] = $v[9];
                        $data["Equality_rate"] = $v[10];
                        $data["Negative_rate"] = $v[11];
                        $data["A_average"] = $v[12];
                        $data["L_average"] = $v[13];
                        $data["Total_Score"] = $v[14];
                    }
                    $data["Season"] = $Season;
                    $data["Type"] = $type;
                    $data["SClassID"] = $SClassID;
                    $data["subID"] = $subID;

                    $ret = ZqScore::updateOrcreate(["Season" => $Season, "TeamID" => $teamid, "Type" => $type], $data);
                    //  unset($data);
                }
                return "success";
            }
        } elseif ($status == -5) {
            $str = json_decode($response)->data;
            $pattern = '/\d*/';
            preg_match_all($pattern, $str, $matches);
            $sonSclass = (array_filter($matches[0]));
            var_export($sonSclass);
            if(!empty($sonSclass))
                foreach ($sonSclass as $s) {
                    echo "子联赛id：$s<br>+++++++++<br><br>";
                    self::integralStatistic($id . "&subID=$s");
                    echo "<br>+++++++++<br><br>";
                }
        } else {
            return $response;
        }
    }

    //射手榜
    public function topScorer($idID = '',$season=""){

        $seasonid =   !empty($season)?"&season=$season":"";
        // $result =   ($this->get('TopScorer.aspx',"ID=$idID"));
        $result =   ($this->get('TopScorer.aspx',"ID=$idID".$seasonid));

        $res    =   json_decode($result);
        if($res->status==1){
            if(!empty($res->data)&&property_exists($res->data,"i")&&count($res->data->i)>0){
                $data   =  $res->data->i;
                foreach ($data as $v){
                    $newdata["season"] =  $season;
                    $newdata["SclassID"] =  $idID;
                    $newdata["playerID"]    =  $this->checkEmpty($v->playerid);
                    $newdata["player_F"]    =  $this->checkEmpty($v->player_f);
                    $newdata["player_J"]    =  $this->checkEmpty($v->player_j);

                    $newdata["country"]    =  $this->checkEmpty($v->Country);
                    $newdata["teamID"]    =  $this->checkEmpty($v->teamid);
                    $newdata["teamName_F"]    =  $this->checkEmpty($v->teamName_F);
                    $newdata["teamName_J"]    =  $this->checkEmpty($v->teamName_J);

                    $newdata["totalGoals"]    =  $this->checkEmpty($v->goals);
                    $newdata["homeGoals"]    =  $this->checkEmpty($v->homeGoals);
                    $newdata["guestGoals"]    =  $this->checkEmpty($v->awayGoals);
                    $newdata["homePenalty"]    =  $this->checkEmpty($v->homePenalty);
                    $newdata["guestPenalty"]    =  $this->checkEmpty($v->awayPenalty);
                    ZqTopScorer::updateOrcreate(["playerID"=>$newdata["playerID"]],$newdata);
                }
                return "success";
            }
            return $result;
        }
    }

    //填充射手榜
    public function fillTopScorer(){
        //获取联赛数据

        $id = (int)file_get_contents(storage_path('cache/TotalSClassID'));
        $classlist = ZqSclass::where(["Type" => 1])->where("SClassID", ">=", $id)->orderBy('SClassID')->limit(10)->get(["SClassID"])->toarray();

        if (count($classlist) > 0)
            foreach ($classlist as $v) {

                //赛季信息
                $seasons    =   ZqSubSclass::where(["SclassID"=>$v["SClassID"]])->groupby("includeSeason")->orderby("includeSeason","desc")->get(["includeSeason"])->first();

                if(empty($seasons["includeSeason"])){ file_put_contents(storage_path('cache/TotalSClassID'), $v["SClassID"]+1); continue;}
                $seasons    =   explode(",",$seasons["includeSeason"]);
                if(!empty($seasons))
                    foreach ($seasons as $s){
                        $this->TopScorer($v["SClassID"],$s);}

                $v["SClassID"]= $v["SClassID"]==$id?$v["SClassID"]+1:$v["SClassID"];
                var_export( $v["SClassID"]);
                file_put_contents(storage_path('cache/TotalSClassID'), $v["SClassID"]);
                echo "*****************SClassID:" . $v["SClassID"] . "***************<br>";
            }
    }

    /**
     * 填充球员信息通过teamid
     * @param Request $request
     * @return string
     */
    public function fillFootBallPlayer(Request $request)
    {
        //获取已完成球队最后的id

        $teamid = (int)file_get_contents(storage_path('cache/teamid'));
        //取出球队
        //   $list   =   FootBallTeam::where("TeamID",$teamid)->offset($teamid)->limit(2);
        $list = DB::table("zq_team")->where("TeamID", ">=", $teamid)->orderBy('TeamID', 'asc')->limit(20)->get(["TeamID"]);

        foreach ($list as $v) {
            $this->getFootBallPlayerInfo( $v->TeamID);
            file_put_contents(storage_path('cache/teamid'), $v->TeamID);
            $info[] = date("Y-m-d H:i:s") . "  teamId:" . $v->TeamID . "<br>";
        }
        return json_encode($info);
    }

    public function fillFootBallPlayerByDay(){
        return    $this->getFootBallPlayerInfo( $day=1,$mode='day');
    }

    //积分数据填充
    public function fillZqScore()
    {
        //获取联赛数据
        $id = (int)file_get_contents(storage_path('cache/sclassid'));
        $classlist = ZqSclass::where(["Type" => 1])->where("SClassID", ">=", $id)->orderBy('SClassID')->limit(5)->get(["SClassID"])->toarray();

        if (count($classlist) > 0)
            foreach ($classlist as $v) {
                $this->integralStatistic($v["SClassID"]);
                file_put_contents(storage_path('cache/sclassid'), $v["SClassID"]);
                echo "SClassID:" . $v["SClassID"] . "********************************<br>";
            }
    }




    public function get($uri, string $other = '', $token = '12312313123')
    {

        $path = $uri . '?token=' . $token . '&' . $other;
        echo "======>>" . $path . "<br>";
        $realpath = storage_path('cache/' . md5($path));

        $has = file_exists($realpath);
        $filectime = $has ? filectime($realpath) : time() + 10;
        $filectime = time() - $filectime;

        if ($has && $filectime > 0) {
            return file_get_contents($realpath);
        }

        getdata:
        $client = new  Client (['base_uri' => 'http://sapi.meme100.com/zq/', 'timeout' => 300,]);
        $response = $client->request('GET', $uri . '?token=' . $token . '&' . $other);
        $data = $response->getBody();

        if (json_decode($data)->status == -5) {
            $sleep = rand(5, 30);
            sleep($sleep);
            $str = json_decode($data)->data;
            if($str=='取不到相关数据')return $data;

            if (strpos($str, "ub:")) {
                echo "<br>有子联赛<br>";
                return $data;
            }
            echo date("Y-m-d H:i:s");
            echo $path;
            echo "-------" . $data;

            exit();
            // goto getdata;
        }

        $has ? unlink($realpath) : "";
        file_put_contents($realpath, $data);

        return ($data);
    }

    public function checkEmpty($value)
    {

        return !empty($value) ? (is_array($value) || is_object($value) ? json_encode($value) : trim($value)) : null;
    }

    public function test($url='http://info.nowscore.com/cn/team/',$uri = 'player.aspx?', string $other = 'ID=36', $token = '12312313123')
    {



        //  return view("test");
        $html=file_get_contents($url.$uri."playerid=1967&TeamID=150");
        $teamid =   150;
        //获取基础资料
        $pattern1 = '/<strong>?.+?<\/strong>/is';
        $pattern2 = '/<strong>.*?<\/strong>/is';

        preg_match_all($pattern1, $html, $results);
        array_pop($results[0]);
        foreach ($results[0] as $v){
            preg_match_all($pattern2, $v, $r);
            if(count($r[0])==1){
                $data[]    =   preg_replace('/<\/?strong>|<\/?tr>|<\/td>|<span class=\"STYLE13\">|<td.*."#fbfbfb">/',"",$r[0]);
            }else{
                foreach ($r[0] as $d){
                    $data[]    =   preg_replace('/<\/?strong>|<\/?tr>|<\/td>|<span class=\"STYLE13\">|<td.*."#fbfbfb">/',"",$d);
                }

            }
        }
        //获取图片

        $img_pattern    =   '/<img alt="" src=".*?." id="imgPhoto"/is';
        preg_match($img_pattern, $html, $img);
        $data[]    =   preg_replace('/<img alt="" src="\/Image\/player\/|" id="imgPhoto"/',"",$img[0]);


        //位置信息
        //拿到js url
        $url_pattern    =   '/version=.*?"><\/script>/is';
        preg_match($url_pattern, $html, $js_url);
        $version =preg_replace('/version=|"><\/script>/',"",$js_url[0]);
        $jsurl  ='http://info.nowscore.com/jsData/teamInfo/teamDetail/tdl'.$teamid.'.js?version='.$version;

        $jsarray    =   file_get_contents($jsurl);

        $jsarray    =   str_replace("var "," $",$jsarray);
        $jsarray    =   str_replace("﻿","",$jsarray);

        Storage::put("cache/jsdata.php","<?php".PHP_EOL.trim( $jsarray));
        include storage_path("app/cache/jsdata.php");
        var_export($vanguard);
    }


}
