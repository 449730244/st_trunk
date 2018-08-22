<?php

namespace App\Http\Controllers;
set_time_limit(0);

use App\Models\LqJifen;
use App\Models\LqPlayer;
use App\Models\LqSchedule;
use App\Models\LqSclass;
use App\Models\LqSclassInfo;
use App\Models\LqTeahnicCount;
use App\Models\LqTeam;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
class GetLqBaseDataController extends Controller
{

    //获取篮球球员基础数据
    public function fillPlayerData(){
        $result = json_decode($this->get("LqPlayer_xml.aspx"));

       if(is_object($result)&&!empty($result->data)){
          if(property_exists($result->data,"i")){
              foreach ($result->data->i as $v){

                  $data   =   [
                      "id"=>$this->checkEmpty($v->id),
                      "Number"=>$this->checkEmpty($v->Number),
                      "Name_F"=>$this->checkEmpty($v->Name_F),
                      "Name_JS"=>$this->checkEmpty($v->Name_JS),
                      "Name_J"=>$this->checkEmpty($v->Name_J),
                      "Name_E"=>$this->checkEmpty($v->Name_E),
                      "TeamID"=>$this->checkEmpty($v->TeamID),
                      "Place"=>$this->checkEmpty($v->Place),
                      "Birthday"=>$this->checkEmpty($v->Birthday),
                      "Tallness"=>$this->checkEmpty($v->Tallness),
                      "Weight"=>$this->checkEmpty($v->Weight),
                      "Photo"=>$this->checkEmpty($v->Photo),
                      "NbaAge"=>$this->checkEmpty($v->NbaAge),
                  ];
                  LqPlayer::updateOrcreate(["id"=>$v->id],$data);
              }
          }else{
              $v  =   $result->data;
            $data   =   [
                "id"=>$v->id,
                "Number"=>$v->Number,
                "Name_F"=>$v->Name_F,
                "Name_JS"=>$v->Name_JS,
                "Name_J"=>$v->Name_J,
                "Name_E"=>$v->Name_E,
                "TeamID"=>$v->TeamID,
                "Place"=>$v->Place,
                "Birthday"=>$v->Birthday,
                "Tallness"=>$v->Tallness,
                "Weight"=>$v->Weight,
                "Photo"=>$v->Photo,
                "NbaAge"=>$v->NbaAge,
            ];
              LqPlayer::updateOrcreate(["id"=>$v->id],$data);
          }
           return "success";
       }

       return $result;
    }

    //获取球队信息
    public function fillTeamData(){
        $result = json_decode($this->get("LqTeam_Xml.aspx"));
        if(is_object($result)&&!empty($result->data)){
            if(property_exists($result->data,"i")){
                foreach ($result->data->i as $v){

                    $data   =   [
                        "id"=>$this->checkEmpty($v->id),
                        "lsID"=>$this->checkEmpty($v->lsID),
                        "Name_JS"=>$this->checkEmpty($v->short),
                        "Name_J"=>$this->checkEmpty($v->gb),
                        "Name_F"=>$this->checkEmpty($v->big5),
                        "Name_E"=>$this->checkEmpty($v->en),
                        "Logo"=>$this->checkEmpty($v->logo),
                        "URL"=>$this->checkEmpty($v->url),
                        "LocationID"=>(int)$this->checkEmpty($v->LocationID),
                        "MatchAddrID"=>(int)$this->checkEmpty($v->MatchAddrID),
                        "Gymnasium"=>$this->checkEmpty($v->Gymnasium),
                        "City"=>$this->checkEmpty($v->City),
                        "Capacity"=>$this->checkEmpty($v->Capacity),
                        "JoinYear"=>(int)$this->checkEmpty($v->JoinYear),
                        "FirstTime"=>(int)$this->checkEmpty($v->FirstTime),
                        "Drillmaster"=>$this->checkEmpty($v->Drillmaster),
                    ];
                    LqTeam::updateOrcreate(["id"=>$v->id],$data);

                }
            }else{
                $v  =   $result->data;
                $data   =   [
                    "id"=>$this->checkEmpty($v->id),
                    "lsID"=>$this->checkEmpty($v->lsID),
                    "Name_JS"=>$this->checkEmpty($v->short),
                    "Name_J"=>$this->checkEmpty($v->gb),
                    "Name_F"=>$this->checkEmpty($v->big5),
                    "Name_E"=>$this->checkEmpty($v->en),
                    "Logo"=>$this->checkEmpty($v->logo),
                    "URL"=>$this->checkEmpty($v->url),
                    "LocationID"=>(int)$this->checkEmpty($v->LocationID),
                    "MatchAddrID"=>(int)$this->checkEmpty($v->MatchAddrID),
                    "Gymnasium"=>$this->checkEmpty($v->Gymnasium),
                    "City"=>$this->checkEmpty($v->City),
                    "Capacity"=>$this->checkEmpty($v->Capacity),
                    "JoinYear"=>(int)$this->checkEmpty($v->JoinYear),
                    "FirstTime"=>(int)$this->checkEmpty($v->FirstTime),
                    "Drillmaster"=>$this->checkEmpty($v->Drillmaster),
                ];
                LqTeam::updateOrcreate(["id"=>$v->id],$data);
            }
            return "success";
        }
    }

    //积分、联盟排名
    public function getJifen(array $param){

        $result = json_decode($this->get("LqRankings.aspx",$other="sclassid=".$param['sclassid']."&season=".$param['season']));

        if(is_object($result)&&!empty($result->data)){
            if(property_exists($result->data,"i")){
                foreach ($result->data->i as $v){

                    if(!property_exists($v,"league")){
                       // var_dump($v);exit();
                        return ;
                    }
                    $data   =   [
                        "TeamID"=>$this->checkEmpty($v->TeamID),
                        "league"=>$this->checkEmpty($v->league),
                        "Name"=>$this->checkEmpty($v->Name),
                        "matchSeason"=>$this->checkEmpty($v->matchSeason),
                        "homewin"=>$this->checkEmpty($v->homewin),
                        "homeloss"=>$this->checkEmpty($v->homeloss),
                        "awaywin"=>$this->checkEmpty($v->awaywin),
                        "awayloss"=>$this->checkEmpty($v->awayloss),
                        "WinScale"=>(int)$this->checkEmpty($v->WinScale),
                        "state"=>(int)$this->checkEmpty($v->state),
                        "homeOrder"=>$this->checkEmpty($v->homeOrder),
                        "awayOrder"=>$this->checkEmpty($v->awayOrder),
                        "TotalOrder"=>$this->checkEmpty($v->TotalOrder),
                        "HomeScore"=>(int)$this->checkEmpty($v->HomeScore),
                        "HomeLossScore"=>(int)$this->checkEmpty($v->HomeLossScore),
                        "awayScore"=>$this->checkEmpty($v->awayScore),
                        "awayLossScore"=>$this->checkEmpty($v->awayLossScore),
                        "Near10Win"=>$this->checkEmpty($v->Near10Win),
                        "Near10loss"=>$this->checkEmpty($v->Near10loss),
                        "sclassid"=>$this->checkEmpty($param['sclassid']),
                    ];
                    LqJifen::updateOrcreate(["TeamID"=>$v->TeamID],$data);

                }
            }else{
                $v  =   $result->data;

               if(!property_exists($v,"TeamID")) return ;
                $data   =   [
                    "TeamID"=>$this->checkEmpty($v->TeamID),
                    "league"=>$this->checkEmpty($v->league),
                    "Name"=>$this->checkEmpty($v->Name),
                    "matchSeason"=>$this->checkEmpty($v->matchSeason),
                    "homewin"=>$this->checkEmpty($v->homewin),
                    "homeloss"=>$this->checkEmpty($v->homeloss),
                    "awaywin"=>$this->checkEmpty($v->awaywin),
                    "awayloss"=>$this->checkEmpty($v->awayloss),
                    "WinScale"=>(int)$this->checkEmpty($v->WinScale),
                    "state"=>(int)$this->checkEmpty($v->state),
                    "homeOrder"=>$this->checkEmpty($v->homeOrder),
                    "awayOrder"=>$this->checkEmpty($v->awayOrder),
                    "TotalOrder"=>$this->checkEmpty($v->TotalOrder),
                    "HomeScore"=>(int)$this->checkEmpty($v->HomeScore),
                    "HomeLossScore"=>(int)$this->checkEmpty($v->HomeLossScore),
                    "awayScore"=>$this->checkEmpty($v->awayScore),
                    "awayLossScore"=>$this->checkEmpty($v->awayLossScore),
                    "Near10Win"=>$this->checkEmpty($v->Near10Win),
                    "Near10loss"=>$this->checkEmpty($v->Near10loss),
                ];
                LqJifen::updateOrcreate(["TeamID"=>$v->TeamID],$data);
            }
            return "success";
        }
    }

    public function fillJifen(){
        //获取当前sclassid
        $id =   file_exists(storage_path("/cache/lq_jifen"))?file_get_contents(storage_path("/cache/lq_jifen")):file_put_contents(storage_path("/cache/lq_jifen"),1);
       $list   =LqSclass::where("SClassID",">=","$id")->orderby("SClassID","asc")->limit(10)->get(["SClassID","Curr_matchSeason"]);

       foreach ($list as $v){
           $res     =    $this->getJifen(["sclassid"=>$v["SClassID"],"season"=>$v["Curr_matchSeason"]]);
           file_put_contents(storage_path("/cache/lq_jifen"),$v["SClassID"]);
           echo $res;
       }
    }


    //技术统计

    public function LqTeahnicCount($param){
        $result    =   json_decode( $this->get("LqTeahnicCount.aspx","id=".$param["id"]));
        if(is_object($result)&&!empty($result->data)) {
            $info   =   $result->data;

            $data   =   [
                "hometeamID"=>intval($this->checkEmpty($info->hometeamID)),
                "hometeam"=>$this->checkEmpty($info->hometeam),
                "homescore"=>$this->checkEmpty($info->homescore),
                "homefast"=>$this->checkEmpty($info->hometeamID),
                "homeinside"=>$this->checkEmpty($info->homeinside),
                "homeExceed"=>$this->checkEmpty($info->homeExceed),
                "twoattack"=>$this->checkEmpty($info->twoattack),
                "totalmis"=>$this->checkEmpty($info->totalmis),
                "guestteamID"=>$this->checkEmpty($info->guestteamID),
                "guestteam"=>$this->checkEmpty($info->guestteam),
                "guestscore"=>$this->checkEmpty($info->guestscore),
                "guestfast"=>$this->checkEmpty($info->guestfast),
                "guestExceed"=>$this->checkEmpty($info->guestExceed),
                "guestinside"=>$this->checkEmpty($info->guestinside),
                "guestTwoattack"=>$this->checkEmpty($info->guestTwoattack),
                "guestTotalmis"=>$this->checkEmpty($info->guestTotalmis),
                "matchtime"=>$this->checkEmpty($info->matchtime),
                "costtime"=>$this->checkEmpty($info->costtime),
                 "HomePlayerList"=>!empty($info->HomePlayerList)?json_encode($info->HomePlayerList->I):null,
                 "GuestPlayerList"=>!empty($info->GuestPlayerList)?json_encode($info->GuestPlayerList->I):null,
                 "id"=>$param["id"],
            ];

            LqTeahnicCount::updateOrcreate(["hometeamID"=>$info->hometeamID],$data);
        }
    }

    public function fillTeahnicCount(){
        $list   = LqSchedule::where("created_at",">=",date("Y-m-d ")."00:00:00")->get(["ScheduleID"]);
        if(!empty($list))
        foreach ($list as $v){
            $this->LqTeahnicCount(["id"=>$v["ScheduleID"]]);
        }

    }



    public function test(){

     return file_get_contents("http://120.198.143.213:8071/phone/analysis/1/59/cn/1599657.htm?r=1534226053&from=2");

        return $this->todayDdata();
        $response    =   ( $this->get("today.aspx"));
        return $response;
    }

    //get请求
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
        $client = new  Client(['base_uri' => 'http://sapi.meme100.com/lq/', 'timeout' => 30,]);
        $response = $client->request('GET', $uri . '?token=' . $token . '&' . $other);
        $data = $response->getBody();

        if (json_decode($data)->status == -5) {
            $sleep = rand(5, 30);
            sleep($sleep);
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
        if(!empty($value)){
            if(is_array($value) || is_object($value)){

                    foreach ($value as $v)
                        return trim($v)?trim($v):null;
                }
            return trim($value)?trim($value):null;
        }
        return null;

    }
}
