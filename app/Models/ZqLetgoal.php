<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use DB;
class ZqLetgoal extends Model
{
    protected $table    =   'zq_letgoal';

    protected  $primaryKey='Indentity';
    public  $timestamps=false;
    protected $fillable = [  
        "ScheduleID",
        "CompanyID",
        "FirstGoal",
        "FirstUpOdds",
        "FirstDownOdds",
        "Goal",
        "UpOdds",
        "DownOdds",
        "Goal_Real",
        "UpOdds_Real",
        "DownOdds_Real",
        "ModifyTime",
        "Result",
        "ClosePan",
        "ZouDi",
        "Running",
        "isStopLive"
    ];

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
    public function writedata($ScheduleID)
    {
        //http://score.nowscore.com/odds/match.aspx?id=1576729 赔率三合一
        $result = getCurl('http://score.nowscore.com/odds/match.aspx?id='.$ScheduleID);
        $reg = '/<div id="odds"><TABLE cellSpacing=1 cellPadding=0 width=942 align=center bgColor=#bbbbbb border=0>(.*?)<\/TABLE><\/div>/i';
        preg_match($reg,$result,$cth);
        $preg = "/<tr.*?>(.*?)<\/tr>/ism";
        preg_match_all($preg,$cth['1'],$matches);
        $content = $matches['1'];
        if(empty($content)) return;
        $ar = array();
        foreach ($content as $k => $v) {
            if ($k > 1 && $k < (count($content)-2)) {
                $v = strip_tags(str_replace('</TD>','</TD>|',$v));
                $ar[] = $v;
            }
        }
        $data_list=array();
        foreach ($ar as $k => $v) {
            $dar = explode("|",$v);
            $cm = explode('                ',$dar['0']);
            $ZouDi = 0;
            if (!empty($cm['1']) && $cm['1'] == '走地') $ZouDi = 1;
            $company_id = $this->get_company_id($cm['0']);
            $data_list[]=array(
                "ScheduleID"=>$ScheduleID,
                "CompanyID"=>$company_id,
                "FirstGoal"=>$dar['2'],
                "FirstUpOdds"=>floatval($dar['1']),
                "FirstDownOdds"=>floatval($dar['3']),
                "Goal"=>$dar['5'],
                "UpOdds"=>floatval($dar['4']),
                "DownOdds"=>floatval($dar['6']),
                // "Goal_Real"=>'',
                // "UpOdds_Real"=>'',
                // "DownOdds_Real"=>'',
                // "ModifyTime"=>'',
                // "Result"=>'',
                //"ClosePan"=>$ClosePan,
                "ZouDi"=>$ZouDi,
                "Running"=>$ZouDi,
                //"isStopLive"=>$ClosePan,
            );
        }
        if (!empty($data_list)) {
            foreach ($data_list as $k => $v) {
                ZqLetgoal::updateOrCreate(['ScheduleID' => $v["ScheduleID"],'CompanyID' => $v["CompanyID"]] , $v);
            }        
        }      
    }
    public function get_company_id($name){
            return DB::table('zq_company')->where('Name',$name)->value('CompanyID');
    }

}