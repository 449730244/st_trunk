<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class ZqStandardDetail extends Model
{
    protected $table='zq_standard_detail';
    protected $primaryKey='ID';
    protected $guarded = [];

    public function get_standard_detail_data(){
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
        $gameDetail_info = '';
        foreach ($ar_content as $ke => $vt) {
            if (strstr($vt,'gameDetail=Array("')) $gameDetail_info = str_replace(array('gameDetail=Array("','");'),'',$vt);   
        }
        if (empty($gameDetail_info) ) return;
        $gameDetail_ar = explode('","',$gameDetail_info);        
        $data_detail = array();
        if (!empty($gameDetail_ar)) {
            foreach ($gameDetail_ar as $k => $v) {
              $cd = explode(';',$v);

              if (!empty($cd)) {
                  foreach ($cd as $key => $value) {
                      $cdar = explode("|",$value);
                      if (count($cdar) == 7) {
                          $OddsID = explode('^',explode("|",$cd['0'])['0'])['0'];
                          $HomeWin = $cdar['0'];
                          if ($key == '0')  $HomeWin = explode('^',explode("|",$cd['0'])['0'])['1'];
                          $data_detail[]=array(
                              "OddsID"=>$OddsID,
                              "HomeWin"=>$HomeWin,
                              "Standoff"=>$cdar['1'],
                              "GuestWin"=>$cdar['2'],
                              "ModifyTime"=>$cdar['3'],
                          );
                      }
                      
                  }
              }
            }
        }
        if (!empty($data_detail)) {
            foreach ($data_detail as $k => $v) {
              ZqStandardDetail::updateOrCreate(['OddsID' => $v["OddsID"],'HomeWin'=>$v["HomeWin"],'Standoff'=>$v["Standoff"],'GuestWin'=>$v["GuestWin"],'ModifyTime'=>$v["ModifyTime"]] , $v);
            }
            
        } 
    }
}

