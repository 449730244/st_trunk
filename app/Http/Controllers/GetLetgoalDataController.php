<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\ZqLetgoal;
use App\Models\ZqLetgoalhalf;
use App\Models\ZqStandard;
use App\Models\ZqStandardhalf;
use App\Models\ZqTotalscore;
use App\Models\ZqTotalscorehalf;

use App\Models\LqLetgoal;
use App\Models\LqTotalscore;
use App\Models\LqEuropeodds;
use App\Models\LqLetgoalhalf;
use App\Models\LqTotalscorehalf;
class GetLetgoalDataController extends Controller
{
    protected $client;
    const TOKEN = '12312313123';

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
    }
    /**
     * 足球让球赔率
     *
     */
    public function get_letgoal_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/odds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $data = base64_decode($data);
        $ar = explode('$',$data);
        $ret = explode(';',$ar['2']);//亚赔（让球盘）
        if (!empty($ret)) {
            foreach ($ret as $ke => $val) {
                $dar = explode(',',$val);
                $ClosePan = '0';//封盘
                $ZouDi = '0';//走地
                if ($dar['8'] == true) $ClosePan = '1';
                if ($dar['9'] == true) $ZouDi = '1';
                $ud_data[]=array(
                    "list"=>array(
                        "ScheduleID"=>$dar['0'],
                        "CompanyID"=>$dar['1'],
                        "FirstGoal"=>$dar['2'],
                        "FirstUpOdds"=>$dar['3'],
                        "FirstDownOdds"=>$dar['4'],
                        "Goal"=>$dar['5'],
                        "UpOdds"=>$dar['6'],
                        "DownOdds"=>$dar['7'],
                        // "Goal_Real"=>'',
                        // "UpOdds_Real"=>'',
                        // "DownOdds_Real"=>'',
                        // "ModifyTime"=>'',
                        // "Result"=>'',
                        "ClosePan"=>$ClosePan,
                        "ZouDi"=>$ZouDi,
                        "Running"=>$ZouDi,
                        "isStopLive"=>$ClosePan,
                    ),
                    // "detail"=>array(
                    //     // "OddsID"=>'',
                    //    "UpOdds"=>null,
                    //     "Goal"=>$dar['5'],
                    //    "DownOdds"=>null,
                    //     // "ModifyTime"=>'',
                    //     // "isEarly"=>'',
                    // ),
                );
                
            }
        }else{
            $ud_data = array();
        }
        if (!empty($ud_data)) {
            foreach ($ud_data as $k => $v) {
                ZqLetgoal::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
                // $v['detail']['Indentity'] = $insert->Indentity;
                // ZqLetgoalDetail::updateOrCreate(['Indentity' => $v['detail']['Indentity']] , $v['detail']);
            }        
        }
      
    }
    /**
     * 足球让分半场赔率
     *
     */
    public function get_halfletgoal_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/odds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $data = base64_decode($data);
        $ar = explode('$',$data);
        $half_ret = explode(';',$ar['6']);//半场让球
        if (!empty($half_ret)) {
            foreach ($half_ret as $k => $va) {
                $half_dar = explode(',',$va);
                $half_data[]=array(
                    "list"=>array(
                          "ScheduleID"=>$half_dar['0'],
                          "CompanyID"=>$half_dar['1'],
                          "FirstGoal"=>$half_dar['2'],
                          "FirstUpOdds"=>$half_dar['3'],
                          "FirstDownOdds"=>$half_dar['4'],
                          "Goal"=>$half_dar['5'],
                          "UpOdds"=>$half_dar['6'],
                          "DownOdds"=>$half_dar['7'],
                          // "Goal_Real"=>'',
                          // "UpOdds_Real"=>'',
                          // "DownOdds_Real"=>'',
                          // "ModifyTime"=>'',
                          // "Result"=>'',
                          // "ZouDi"=>'',
                    ),
                    // "detail"=>array(
                    //       // "OddsID"=>'',
                    //       // "UpOdds"=>'',
                    //       "Goal"=>$half_dar['5'],
                    //       // "DownOdds"=>'',
                    //       // "ModifyTime"=>'',
                    //       // "isEarly"=>'',
                    // ),
                );
                
            }
        }else{
            $half_data = array();
        }
        if (!empty($half_data)) {
            foreach ($half_data as $k => $v) {
                ZqLetgoalhalf::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
                // $v['detail']['OddsID'] = $insert->OddsID;
                // ZqLetgoalhalfDetail::updateOrCreate(['OddsID' => $v['detail']['OddsID']] , $v['detail']);
            }
            
        }
    }
    /**
     * 欧赔赔率
     *
     */
    public function get_standard_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/odds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $data = base64_decode($data);
        $ar = explode('$',$data);
        $ou_ret = explode(';',$ar['3']);//欧赔
        if (!empty($ou_ret)) {
            foreach ($ou_ret as $k => $va) {
                $ou_dar = explode(',',$va);
                $ou_data[]=array(
                    "list"=>array(
                          "ScheduleID"=>$ou_dar['0'],
                          "CompanyID"=>$ou_dar['1'],
                          "FirstHomeWin"=>$ou_dar['2'],
                          "FirstStandoff"=>$ou_dar['3'],
                          "FirstGuestWin"=>$ou_dar['4'],
                          // "HomeWin"=>'',
                          // "Standoff"=>'',
                          // "GuestWin"=>'',
                          "HomeWin_R"=>$ou_dar['5'],
                          "GuestWin_R"=>$ou_dar['7'],
                          "Standoff_R"=>$ou_dar['6'],
                          // "Result"=>'',
                          // "ClosePan"=>'',
                          // "ModifyTime"=>'',
                    ),
              
                );
                
            }
        }else{
            $ou_data = array();
        }
        if (!empty($ou_data)) {
            foreach ($ou_data as $k => $v) {
                ZqStandard::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }
            
        }
    }
    /**
     * 半场欧赔赔率
     *
     */
    public function get_standardhalf_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/Odds_1x2_half.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $ou_ret = $data->h;
        if (!empty($ou_ret)) {
            foreach ($ou_ret as $k => $va) {
                $ou_dar = json_decode(json_encode($va->odds->o),true);
                if (!empty($ou_dar)) {
                    if (is_array($ou_dar)) {
                        foreach ($ou_dar as $kt => $vd) {
                            $vt=explode(',',trim($vd));
                            $ou_data[]=array(
                              "ScheduleID"=>$va->id,
                              "CompanyID"=>$vt['0'],
                              "FirstHomeWin"=>$vt['2'],
                              "FirstStandoff"=>$vt['3'],
                              "FirstGuestWin"=>$vt['4'],
                              // "HomeWin",
                              // "Standoff",
                              // "GuestWin",
                              "HomeWin_R"=>$vt['5'],
                              "GuestWin_R"=>$vt['6'],
                              "Standoff_R"=>$vt['7'],
                              "ModifyTime"=>$vt['8'],
                             );

                        }
                    }else{
                        $vt=explode(',',trim($ou_dar));
                        $ou_data[]=array(
                          "ScheduleID"=>$va->id,
                          "CompanyID"=>$vt['0'],
                          "FirstHomeWin"=>$vt['2'],
                          "FirstStandoff"=>$vt['3'],
                          "FirstGuestWin"=>$vt['4'],
                          // "HomeWin",
                          // "Standoff",
                          // "GuestWin",
                          "HomeWin_R"=>$vt['5'],
                          "GuestWin_R"=>$vt['6'],
                          "Standoff_R"=>$vt['7'],
                          "ModifyTime"=>$vt['8'],
                          );
                    }
                    
                }else{
                    continue;
                }     
            }
        }else{
            $ou_data = array();
        }
        if (!empty($ou_data)) {
            foreach ($ou_data as $k => $v) {
                ZqStandardhalf::updateOrCreate(['ScheduleID' => $v["ScheduleID"],'CompanyID' => $v["CompanyID"]] , $v);
            }
            
        }
    }
    /**
     * 足球大小球赔率
     *
     */
    public function get_totalscore_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/odds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $ar_data = $obj->data;
        $ar_data = base64_decode($ar_data);
        $ar = explode('$',$ar_data);
        $ret = explode(';',$ar['4']);
        if (!empty($ret)) {
            foreach ($ret as $k => $va) {
                $dar = explode(',',$va);
                $data[]=array(
                    "list"=>array(
                          "ScheduleID"=>$dar['0'],
                          "CompanyID"=>$dar['1'],
                          "FirstGoal"=>$dar['2'],
                          "FirstUpOdds"=>$dar['3'],
                          "FirstDownOdds"=>$dar['4'],
                          "Goal"=>$dar['5'],
                          "UpOdds"=>$dar['6'],
                          "DownOdds"=>$dar['7'],
                          // "Goal_real",
                          // "UpOdds_real",
                          // "DownOdds_real",
                          // "ClosePan",
                          // "Result",
                          // "ModifyTime",
                          // "ZouDi",
                          // "isStopLive",
                    ),
                );
            }
        }else{
            $data = array();
        }
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                ZqTotalscore::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }   
        }
    }
    /**
     * 足球半场大小球赔率
     *
     */
    public function get_totalscorehalf_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/odds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $ar_data = $obj->data;
        $ar_data = base64_decode($ar_data);
        $ar = explode('$',$ar_data);
        $ret = explode(';',$ar['7']);
        if (!empty($ret)) {
            foreach ($ret as $k => $va) {
                $dar = explode(',',$va);
                $data[]=array(
                    "list"=>array(
                          "ScheduleID"=>$dar['0'],
                          "CompanyID"=>$dar['1'],
                          "FirstGoal"=>$dar['2'],
                          "FirstUpOdds"=>$dar['3'],
                          "FirstDownOdds"=>$dar['4'],
                          "Goal"=>$dar['5'],
                          "UpOdds"=>$dar['6'],
                          "DownOdds"=>$dar['7'],
                          // "Goal_real",
                          // "UpOdds_real",
                          // "DownOdds_real",
                          // "ModifyTime",
                          // "ZouDi"
                    ),
                );
            }
        }else{
            $data = array();
        }
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                ZqTotalscorehalf::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }   
        }
    }

    /**
     * 篮球让球赔率（让分赔率）
     * 示例：114908,2,1.5,0.87,0.87,1.5,0.87,0.87,,,;
        比赛ID
        公司ID
        初盘盘口 （第一个赔率）
        主队初盘赔率
        客队初盘赔率
        即时盘口（当前赔率，不包括走地）
        主队即时赔率
        客队即时赔率
        走地盘口
        主队走地赔率
        客队走地赔率
     *
     */
    public function get_lq_letgoal_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'lq/LqOdds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $ar = explode('$',$data);
        $ret = explode(';',$ar['2']);
        if (!empty($ret)) {
            foreach ($ret as $ke => $val) {
                $dar = explode(',',$val);
                $ud_data[]=array(
                    "list"=>array(
                        "ScheduleID"=>$dar['0'],
                        "CompanyID"=>$dar['1'],
                        // "HomeOdds"=>'',
                        // "LetGoal"=>'',
                        // "GuestOdds"=>'',
                        "HomeOdds_F"=>$dar['3'],
                        "LetGoal_F"=>$dar['2'],
                        "GuestOdds_F"=>$dar['4'],
                        // "ModifyTime"=>'',
                        "HomeOdds_R"=>$dar['6'],
                        "Goal_R"=>$dar['5'],
                        "GuestOdds_R"=>$dar['7'],
                        // "create_time"=>'',
                        // "update_time"=>'',
                    )
                );
                
            }
        }else{
            $ud_data = array();
        }
        if (!empty($ud_data)) {
            foreach ($ud_data as $k => $v) {
                LqLetgoal::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }        
        }
      
    }

    /**
     * 篮球大小球赔率（大小球）
     * 示例：116185,12,125.5,0.909,0.909,125,0.909,0.909,125.5,0.909,0.909,125,0.909,0.909
比赛ID
公司ID
第1节盘口
第1节大分赔率
第1节小分赔率
第2节盘口
第2节大分赔率
第2节小分赔率
第3节盘口
第3节大分赔率
第3节小分赔率
第4节盘口
第4节大分赔率
第4节小分赔率
     * 
     *
     */
    public function get_lq_totalscore_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'lq/PartOdds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;

        $ar = explode('$',$data);
        $ret = array();
        if (!empty($ar['1'])) {
           $ret = explode(';',$ar['1']);
        }
        if (!empty($ret)) {
            foreach ($ret as $ke => $val) {
                $dar = explode(',',$val);
                $ud_data[]=array(
                    "list"=>array(
                        "ScheduleID"=>$dar['0'],
                        "CompanyID"=>$dar['1'],
                        // "LowOdds"=>,
                        // "totalScore"=>,
                        // "HighOdds"=>,
                        // "ModifyTime"=>,
                        "LowOdds1"=>round($dar['4'],2),
                        "LowOdds2"=>floatval($dar['7']),
                        "LowOdds3"=>floatval($dar['10']),
                        "LowOdds4"=>floatval($dar['13']),
                        // "LowOddsHalf"=>,
                        // "LowOdds_R"=>,
                        "TotalScore1"=>floatval($dar['2']),
                        "TotalScore2"=>floatval($dar['5']),
                        "TotalScore3"=>floatval($dar['8']),
                        "TotalScore4"=>floatval($dar['11']),
                        // "TotalScoreHalf"=>,
                        // "TotalScore_R"=>,
                        "HighOdds1"=>floatval($dar['3']),
                        "HighOdds2"=>floatval($dar['6']),
                        "HighOdds3"=>floatval($dar['9']),
                        "HighOdds4"=>floatval($dar['12']),
                        // "HighOddsHalf"=>,
                        // "HighOdds_R"=>,
                    )
                );
            }
        }else{
            $ud_data = array();
        }
        if (!empty($ud_data)) {
            foreach ($ud_data as $k => $v) {
                LqTotalscore::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }        
        }
      
    }

      /**
     * 篮球欧赔赔率
     * 示例：116185,12,125.5,0.909,0.909,125,0.909,0.909,125.5,0.909,0.909,125,0.909,0.909
      比赛ID
      公司ID
      第1节盘口
      第1节大分赔率
      第1节小分赔率
      第2节盘口
      第2节大分赔率
      第2节小分赔率
      第3节盘口
      第3节大分赔率
      第3节小分赔率
      第4节盘口
      第4节大分赔率
      第4节小分赔率

              <h>
<id>比赛ID</id>
<time>比赛时间</time>
<league>联赛英文名,繁体名,简体名</league>
<home>主队英文名,繁体名,简体名</home>
<away>客队英文名,繁体名,简体名</away>
<odds>
<o>博彩公司ID,博彩公司名,初盘主胜,初盘客胜,主胜,客胜,变化时间,赔率ID</o>
<o>博彩公司ID,博彩公司名,初盘主胜,初盘客胜,主胜,客胜,变化时间,赔率ID</o>
<o>博彩公司ID,博彩公司名,初盘主胜,初盘客胜,主胜,客胜,变化时间,赔率ID</o>
……………
</odds>
</h>
胜率，返回率计算方法：
主队胜率=(float)Math.Round(1/(1+主胜赔率/客胜赔率)*100,2);
客队胜率=(float)Math.Round(1/(1+客胜赔率/主胜赔率)*100,2);
返 回 率=(float)Math.Round(主胜胜率*主队赔率,2);
凯利指数：1.52*57.13%=0.925   2.15*42.87%=0.922
     * 
     *
     */
    public function get_lq_europeodds_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'lq/1x2.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $ou_ret = $data->h;
        if (!empty($ou_ret)) {
            foreach ($ou_ret as $k => $va) {
              if (empty($va->odds)) continue;
                $ou_dar = json_decode(json_encode($va->odds->o),true);
                if (!empty($ou_dar)) {
                    if (is_array($ou_dar)) {
                      $ar = $ou_dar;
                    }else{
                      $ar['0'] = $ou_dar;
                    }
                      $HomeWinRate_all = 0;
                      $GuestWinRate_all = 0;
                      $count = count($ar);
                      foreach ($ar as $key => $value) {
                          $vt=explode(',',trim($value));
                          $HomeWinRate_all += round(1/(1+$vt['4']/$vt['5'])*100,2);//主队胜率
                          $GuestWinRate_all += round(1/(1+$vt['5']/$vt['4'])*100,2);//客队胜率
                      }
                      $avg_HomeWinRate = round($HomeWinRate_all/$count,2);
                      $avg_GuestWinRate = round($GuestWinRate_all/$count,2);
                        foreach ($ar as $kt => $vd) {
                            $vt=explode(',',trim($vd));
                            $home_name_ar = explode(',',$va->home); 
                            $away_name_ar = explode(',',$va->away);
                            //初盘胜率计算
                            $FirstHomeWinRate = round(1/(1+$vt['2']/$vt['3'])*100,2);//主队胜率
                            $FirstGuestWinRate = round(1/(1+$vt['3']/$vt['2'])*100,2);//客队胜率
                            //初盘返还率
                            $FristbackRate = round($FirstHomeWinRate*$vt['2'],2);
                            //即时胜率
                            $HomeWinRate = round(1/(1+$vt['4']/$vt['5'])*100,2);//主队胜率
                            $GuestWinRate = round(1/(1+$vt['5']/$vt['4'])*100,2);//客队胜率
                            //即时返回率
                            $backRate = round($HomeWinRate*$vt['4'],2);
                            //凯利指数
                            $kaili1 = round($vt['4']*$avg_HomeWinRate/100,2); //主队
                            $kaili2 = round($vt['5']*$avg_GuestWinRate/100,2); //客队
                            $ou_data[]=array(
                            "CompanyID"=>$vt['0'],
                            "ScheduleID"=>$va->id,
                            // "HomeTeamID"=>'',
                            // "GuestTeamID"=>'',
                            "HomeTeam"=>"'".$home_name_ar['2']."'",
                            "GuestTeam"=>"'".$away_name_ar['2']."'",
                            "FirstHomeWin"=>$vt['2'],
                            "FirstGuestWin"=>$vt['3'],
                            "HomeWin"=>$vt['4'],
                            "GuestWin"=>$vt['5'],
                            "FirstHomeWinRate"=>$FirstHomeWinRate,
                            "FirstGuestWinRate"=>$FirstGuestWinRate,
                            "FristbackRate"=>$FristbackRate,
                            "HomeWinRate"=>$HomeWinRate,
                            "GuestWinRate"=>$GuestWinRate,
                            "backRate"=>$backRate,
                            "kaili1"=>$kaili1,
                            "kaili2"=>$kaili2,
                            "time1"=>$vt['6'],
                            // "OddsID"=>'',
                            "CompanyName"=>$vt['1'],
                            // "identity"=>'',
                            "time2"=>$va->time, 
                             );
                        }
                    
                    
                }else{
                    continue;
                }     
            }
        }else{
            $ou_data = array();
        }
        if (!empty($ou_data)) {
            foreach ($ou_data as $k => $v) {
                LqEuropeodds::updateOrCreate(['ScheduleID' => $v["ScheduleID"],'CompanyID' => $v["CompanyID"]] , $v);
            }
            
        }
    }
    /**
     * 篮球半球让球赔率
     * 示例：114908,2,1.5,0.87,0.87,1.5,0.87,0.87;
      比赛ID
      公司ID
      初盘盘口 （第一个赔率）
      主队初盘赔率
      客队初盘赔率
      即时盘口（当前赔率，不包括走地）
      主队即时赔率
      客队即时赔率
     * 
     *
     */
    public function get_lq_letgoalhalf_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'lq/LqHalfOdds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;

        $ar = explode('$',$data);
        $ret = array();
        if (!empty($ar['0'])) {
           $ret = explode(';',$ar['0']);
        }
        if (!empty($ret)) {
            foreach ($ret as $ke => $val) {
                $dar = explode(',',$val);
                $ud_data[]=array(
                    "list"=>array(
                        "ScheduleID"=>$dar['0'],
                        "CompanyID"=>$dar['1'],
                        "FirstGoal"=>$dar['2'],
                        "FirstUpOdds"=>$dar['3'],
                        "FirstDownOdds"=>$dar['4'],
                        "Goal"=>$dar['5'],
                        "UpOdds"=>$dar['6'],
                        "DownOdds"=>$dar['7'],
                    )
                );
            }
        }else{
            $ud_data = array();
        }
        if (!empty($ud_data)) {
            foreach ($ud_data as $k => $v) {
                LqLetgoalhalf::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }        
        }
      
    }
        /**
     * 篮球大小球让球赔率
     * 示例：116185,12,125.5,0.909,0.909,125,0.909,0.909
      比赛ID
      公司ID
      初盘盘口（第一个赔率）
      初盘大分赔率
      初盘小分赔率
      即时盘盘口（当前赔率，不包括走地）
      即时盘大分赔率
      即时盘小分赔率
     * 
     *
     */
    public function get_lq_totalscorehalf_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'lq/LqHalfOdds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;

        $ar = explode('$',$data);
        $ret = array();
        if (!empty($ar['0'])) {
           $ret = explode(';',$ar['1']);
        }
        if (!empty($ret)) {
            foreach ($ret as $ke => $val) {
                $dar = explode(',',$val);
                $ud_data[]=array(
                    "list"=>array(
                        "ScheduleID"=>$dar['0'],
                        "CompanyID"=>$dar['1'],
                        "FirstGoal"=>$dar['2'],
                        "FirstUpOdds"=>$dar['3'],
                        "FirstDownOdds"=>$dar['4'],
                        "Goal"=>$dar['5'],
                        "UpOdds"=>$dar['6'],
                        "DownOdds"=>$dar['7'],   
                    )
                );
            }
        }else{
            $ud_data = array();
        }
        if (!empty($ud_data)) {
            foreach ($ud_data as $k => $v) {
                LqTotalscorehalf::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }        
        }
      
    }
}
