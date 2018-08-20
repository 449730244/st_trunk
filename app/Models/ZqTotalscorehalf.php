<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class ZqTotalscorehalf extends Model
{
    protected $table    =   'zq_totalscorehalf';

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
	  "Goal_real",
	  "UpOdds_real",
	  "DownOdds_real",
	  "ModifyTime",
	  "ZouDi"
    ];

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
}