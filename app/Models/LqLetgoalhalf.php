<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class LqLetgoalhalf extends Model
{
    protected $table    =   'lq_letgoalhalf';

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
    ];

    public function get_lq_letgoalhalf_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'lq/LqHalfOdds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;

        $ar = explode('$',$data);
        $ret = array();
        if (!empty($ar['0'])) {
           $ret = explode(';',$ar['0']);
        }
        if (!empty($ret)) {
            foreach ($ret as $ke => $val) {
                $dar = explode(',',$val);
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
                    )
                );
            }
        }else{
            $ud_data = array();
        }
        if (!empty($ud_data)) {
            foreach ($ud_data as $k => $v) {
                LqLetgoalhalf::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }        
        }
      
    }
}