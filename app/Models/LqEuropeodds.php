<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use DB;
class LqEuropeodds extends Model
{
    protected $table    =   'lq_europeodds';

    protected  $primaryKey='identity';

    protected $fillable = [  
        "CompanyID",
        "ScheduleID",
        "HomeTeamID",
        "GuestTeamID",
        "HomeTeam",
        "GuestTeam",
        "FirstHomeWin",
        "FirstGuestWin",
        "HomeWin",
        "GuestWin",
        "FirstHomeWinRate",
        "FirstGuestWinRate",
        "FristbackRate",
        "HomeWinRate",
        "GuestWinRate",
        "backRate",
        "kaili1",
        "kaili2",
        "time1",
        "OddsID",
        "CompanyName",
        "time2", 
    ];
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
        echo '操作成功。';
    }
}