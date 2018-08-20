<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class ZqLetgoal extends Model
{
    protected $table    =   'zq_letgoal';

    protected  $primaryKey='Indentity';

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

}