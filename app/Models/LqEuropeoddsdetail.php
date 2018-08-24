<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class LqEuropeoddsdetail extends Model
{
    protected $table    =   'lq_europeoddsdetail';

    protected  $primaryKey='id';
    protected $guarded = [];

    public function get_lq_europeodds_detail_data(){
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
        $gameDetail_info = '';
        foreach ($ar_content as $ke => $vt) {
            if (strstr($vt,'gameDetail=Array("')) $gameDetail_info = str_replace(array('gameDetail=Array("','")'),'',$vt);  
        }
        if (empty($gameDetail_info)) return;
        $gameDetail_ar = explode('","',$gameDetail_info);       
        $data_detail = array();
        if (!empty($gameDetail_ar)) {
            foreach ($gameDetail_ar as $k => $v) {
              $cd = explode(';',$v);

              if (!empty($cd)) {
                  foreach ($cd as $key => $value) {
                      $cdar = explode("|",$value);
                    
                      if (count($cdar) == 3) {
                        $OddsID = explode('^',explode("|",$cd['0'])['0'])['0'];
                        $HomeWin = $cdar['0'];
                        if ($key == '0')  $HomeWin = explode('^',explode("|",$cd['0'])['0'])['1'];
                        $data_detail[]=array(
                            "OddsID"=>$OddsID,
                              "HomeWin"=>$HomeWin,
                              "GuestWin"=>$cdar['1'],
                              "ModifyTime"=>$cdar['2'],                       
                          );
                      }
                      
                  }
              }
            }
        }
        if (!empty($data_detail)) {
            foreach ($data_detail as $k => $v) {
                LqEuropeoddsdetail::updateOrCreate(['OddsID' => $v["OddsID"],'HomeWin' => $v["HomeWin"],'GuestWin' => $v["GuestWin"],'ModifyTime' => $v["ModifyTime"]] , $v);
            }
            
        }
       
    }
}