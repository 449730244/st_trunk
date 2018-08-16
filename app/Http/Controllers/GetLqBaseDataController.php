<?php

namespace App\Http\Controllers;
set_time_limit(0);
use App\Models\LqPlayer;
use App\Models\LqTeam;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

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
        $result = json_decode($this->get("LqRankings.aspx",$other=""));
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

    public function fillJifen(){
        //获取当前sclassid
        $this->getJifen(["sclassid"=>2,"season"=>2018]);
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
