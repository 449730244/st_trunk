<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class ZqLetgoalhalf extends Model
{
    protected $table    =   'zq_letgoalhalf';

    protected  $primaryKey='OddsID';

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
          "ZouDi"
    ];
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
}