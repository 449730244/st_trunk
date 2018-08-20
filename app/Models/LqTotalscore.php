<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class LqTotalscore extends Model
{
    protected $table    =   'lq_totalscore';

    protected  $primaryKey='OddsID';

    protected $fillable = [  
        "ScheduleID",
        "CompanyID",
        "LowOdds",
        "totalScore",
        "HighOdds",
        "ModifyTime",
        "LowOdds1",
        "LowOdds2",
        "LowOdds3",
        "LowOdds4",
        "LowOddsHalf",
        "LowOdds_R",
        "TotalScore1",
        "TotalScore2",
        "TotalScore3",
        "TotalScore4",
        "TotalScoreHalf",
        "TotalScore_R",
        "HighOdds1",
        "HighOdds2",
        "HighOdds3",
        "HighOdds4",
        "HighOddsHalf",
        "HighOdds_R",
    ];

    public function get_lq_totalscore_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'lq/PartOdds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;

        $ar = explode('$',$data);
        $ret = array();
        if (!empty($ar['1'])) {
           $ret = explode(';',$ar['1']);
        }
        if (!empty($ret)) {
            foreach ($ret as $ke => $val) {
                $dar = explode(',',$val);
                $ud_data[]=array(
                    "list"=>array(
                        "ScheduleID"=>$dar['0'],
                        "CompanyID"=>$dar['1'],
                        // "LowOdds"=>,
                        // "totalScore"=>,
                        // "HighOdds"=>,
                        // "ModifyTime"=>,
                        "LowOdds1"=>round($dar['4'],2),
                        "LowOdds2"=>floatval($dar['7']),
                        "LowOdds3"=>floatval($dar['10']),
                        "LowOdds4"=>floatval($dar['13']),
                        // "LowOddsHalf"=>,
                        // "LowOdds_R"=>,
                        "TotalScore1"=>floatval($dar['2']),
                        "TotalScore2"=>floatval($dar['5']),
                        "TotalScore3"=>floatval($dar['8']),
                        "TotalScore4"=>floatval($dar['11']),
                        // "TotalScoreHalf"=>,
                        // "TotalScore_R"=>,
                        "HighOdds1"=>floatval($dar['3']),
                        "HighOdds2"=>floatval($dar['6']),
                        "HighOdds3"=>floatval($dar['9']),
                        "HighOdds4"=>floatval($dar['12']),
                        // "HighOddsHalf"=>,
                        // "HighOdds_R"=>,
                    )
                );
            }
        }else{
            $ud_data = array();
        }
        if (!empty($ud_data)) {
            foreach ($ud_data as $k => $v) {
                LqTotalscore::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }        
        }
      
    }
}