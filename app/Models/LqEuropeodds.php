<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class LqEuropeodds extends Model
{
    protected $table    =   'lq_europeodds';

    protected  $primaryKey='identity';
    protected $guarded = [];
    public function get_lq_europeodds_data(){
        $ScheduleID_list=DB::table('lq_schedule')->get(['ScheduleID']);
        foreach ($ScheduleID_list as $key => $value) {
            $this->writedata($value->ScheduleID);
            if ($key>0 && $key%500 == 0) sleep(90);
        }
        echo '操作成功';
    }
    public function writedata($ScheduleID)
    {
        //http://score.nowscore.com/nba/odds/1x2.aspx?id=320591 百家欧赔
        $show_1 = substr($ScheduleID,0,1);
        $show_2 = substr($ScheduleID,1,2);
        $content = getCurl('http://nba.nowscore.com/1x2/'.$show_1.'/'.$show_2.'/'.$ScheduleID.'.js');

        if(strstr($content,'</body>')) return;
        $art = [$content];
        $content = str_replace("\r\n", '', $art['0']);
        $content = str_replace(";var ", '|-|', $content);
        $ar_content = explode('|-|',$content);
        // $con = explode('=',$ar_content['25']);
        $game_info = '';
        foreach ($ar_content as $ke => $vt) {
            if (strstr($vt,'game=Array("')) $game_info = str_replace(array('game=Array("','")'),'',$vt);  
        }
        if (empty($game_info)) return;
        $game_ar = explode('","',$game_info);       
        $data_list = array();
        if (!empty($game_ar)) {
            foreach ($game_ar as $k => $v) {
              $cd = explode(';',$v);
              if($k == 0){
                $arf['0'] = $cd[count($cd)-1];
              }else{
                $arf = $cd;
              }
              $HomeTeamID = str_replace('hometeamID=','',explode(';',$game_ar['0'])['6']);
              $GuestTeamID = str_replace('guestteamID=','',explode(';',$game_ar['0'])['7']);
              $HomeTeam = str_replace(array('hometeam_cn=','"'),'',explode(';',$game_ar['0'])['4']);
              $GuestTeam = str_replace(array('guestteam_cn=','"'),'',explode(';',$game_ar['0'])['5']);
              if (!empty($arf)) {
                  foreach ($arf as $key => $value) {
                      $cdar = explode("|",$value);
                      if (count($cdar) == 20) {
                          
                        $data_list[]=array(
                            "CompanyID"=>$cdar['0'],
                            "ScheduleID"=>$ScheduleID,
                            "HomeTeamID"=>$HomeTeamID,
                            "GuestTeamID"=>$GuestTeamID,
                            "HomeTeam"=>"'".$HomeTeam."'",
                            "GuestTeam"=>"'".$GuestTeam."'",

                            "FirstHomeWin"=>floatval($cdar['3']),
                            "FirstGuestWin"=>floatval($cdar['4']),
                            "HomeWin"=>floatval($cdar['8']),
                            "GuestWin"=>floatval($cdar['9']),
                            "FirstHomeWinRate"=>floatval($cdar['5']),
                            "FirstGuestWinRate"=>floatval($cdar['6']),
                            "FristbackRate"=>floatval($cdar['7']),
                            "HomeWinRate"=>floatval($cdar['10']),
                            "GuestWinRate"=>floatval($cdar['11']),
                            "backRate"=>floatval($cdar['12']),
                            "kaili1"=>floatval($cdar['13']),
                            "kaili2"=>floatval($cdar['14']),

                            //"time1"=>'',
                            "OddsID"=>$cdar['1'],
                            "CompanyName"=>$cdar['16'],
                            //"time2"=>'',                        
                          );
                      }
                      
                  }
              }
            }
        }
        if (!empty($data_list)) {
            foreach ($data_list as $k => $v) {
                LqEuropeodds::updateOrCreate(['OddsID' => $v["OddsID"]] , $v);
            }
            
        }
       
    }
}