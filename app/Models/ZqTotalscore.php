<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use DB;
class ZqTotalscore extends Model
{
    protected $table    =   'zq_totalscore';

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
	  "Goal_real",
	  "UpOdds_real",
	  "DownOdds_real",
	  "ClosePan",
	  "Result",
	  "ModifyTime",
	  "ZouDi",
	  "isStopLive",
    ];

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
                          "FirstUpOdds"=>floatval($dar['3']),
                          "FirstDownOdds"=>floatval($dar['4']),
                          "Goal"=>$dar['5'],
                          "UpOdds"=>floatval($dar['6']),
                          "DownOdds"=>floatval($dar['7']),
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
                "FirstGoal"=>$dar['14'],
                "FirstUpOdds"=>floatval($dar['13']),
                "FirstDownOdds"=>floatval($dar['15']),
                "Goal"=>$dar['17'],
                "UpOdds"=>floatval($dar['16']),
                "DownOdds"=>floatval($dar['18']),
                // "Goal_real",
                // "UpOdds_real",
                // "DownOdds_real",
                // "ClosePan",
                // "Result",
                // "ModifyTime",
                "ZouDi"=>$ZouDi,
                // "isStopLive",
                
            );
        }
        if (!empty($data_list)) {
            foreach ($data_list as $k => $v) {
                ZqTotalscore::updateOrCreate(['ScheduleID' => $v["ScheduleID"],'CompanyID' => $v["CompanyID"]] , $v);
            }        
        }      
    }
    public function get_company_id($name){
            return DB::table('zq_company')->where('Name',$name)->value('CompanyID');
    }
}