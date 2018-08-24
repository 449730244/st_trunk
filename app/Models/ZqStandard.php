<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\ZqStandardDetail;
use DB;
class ZqStandard extends Model
{
    protected $table='zq_standard';
    protected $primaryKey='Indentity';
    protected $guarded = [];
    
    public function get_standard_data(){
        $ScheduleID_list=DB::table('zq_schedule')->get(['ScheduleID']);
        foreach ($ScheduleID_list as $key => $value) {
            $this->writedata($value->ScheduleID);
            if ($key>0 && $key%500 == 0) sleep(90);
        }
    }
    public function writedata($ScheduleID)
    {
        //http://score.nowscore.com/1x2/1605075.htm 百家欧赔
        $content = getCurl('http://1x2.nowscore.com/'.$ScheduleID.'.js');

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

              if (!empty($cd)) {
                  foreach ($cd as $key => $value) {
                      $cdar = explode("|",$value);
                      dump($cdar);
                      if (count($cdar) == 24) {
                          
                          $data_list[]=array(
                              "Indentity"=>$cdar['1'],
                              "ScheduleID"=>$ScheduleID,
                              "CompanyID"=>$cdar['0'],
                              "FirstHomeWin"=>floatval($cdar['3']),
                              "FirstStandoff"=>floatval($cdar['4']),
                              "FirstGuestWin"=>floatval($cdar['5']),
                              // "HomeWin"=>'',
                              // "Standoff"=>'',
                              // "GuestWin"=>'',
                              "HomeWin_R"=>floatval($cdar['10']),
                              "GuestWin_R"=>floatval($cdar['12']),
                              "Standoff_R"=>floatval($cdar['11']),
                              // "Result"=>'',
                              // "ClosePan"=>'',
                              // "ModifyTime"=>'',
                          );
                      }
                      
                  }
              }
            }
        }
        if (!empty($data_list)) {
            foreach ($data_list as $k => $v) {
                ZqStandard::updateOrCreate(['Indentity' => $v["Indentity"]] , $v);
            }
            
        }
       
    }

          
}

