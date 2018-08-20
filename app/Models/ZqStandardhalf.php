<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class ZqStandardhalf extends Model
{
    protected $table    =   'zq_standardhalf';

    protected  $primaryKey='OddsID';

    protected $fillable = [  
	  "ScheduleID",
	  "CompanyID",
	  "FirstHomeWin",
	  "FirstStandoff",
	  "FirstGuestWin",
	  "HomeWin",
	  "Standoff",
	  "GuestWin",
	  "HomeWin_R",
	  "GuestWin_R",
	  "Standoff_R",
	  "ModifyTime"
    ];
    
    public function get_standardhalf_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/Odds_1x2_half.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $ou_ret = $data->h;
        if (!empty($ou_ret)) {
            foreach ($ou_ret as $k => $va) {
                $ou_dar = json_decode(json_encode($va->odds->o),true);
                if (!empty($ou_dar)) {
                    if (is_array($ou_dar)) {
                        foreach ($ou_dar as $kt => $vd) {
                            $vt=explode(',',trim($vd));
                            $ou_data[]=array(
                              "ScheduleID"=>$va->id,
                              "CompanyID"=>$vt['0'],
                              "FirstHomeWin"=>$vt['2'],
                              "FirstStandoff"=>$vt['3'],
                              "FirstGuestWin"=>$vt['4'],
                              // "HomeWin",
                              // "Standoff",
                              // "GuestWin",
                              "HomeWin_R"=>$vt['5'],
                              "GuestWin_R"=>$vt['6'],
                              "Standoff_R"=>$vt['7'],
                              "ModifyTime"=>$vt['8'],
                             );

                        }
                    }else{
                        $vt=explode(',',trim($ou_dar));
                        $ou_data[]=array(
                          "ScheduleID"=>$va->id,
                          "CompanyID"=>$vt['0'],
                          "FirstHomeWin"=>$vt['2'],
                          "FirstStandoff"=>$vt['3'],
                          "FirstGuestWin"=>$vt['4'],
                          // "HomeWin",
                          // "Standoff",
                          // "GuestWin",
                          "HomeWin_R"=>$vt['5'],
                          "GuestWin_R"=>$vt['6'],
                          "Standoff_R"=>$vt['7'],
                          "ModifyTime"=>$vt['8'],
                          );
                    }
                    
                }else{
                    continue;
                }     
            }
        }else{
            $ou_data = array();
        }
        if (!empty($ou_data)) {
            foreach ($ou_data as $k => $v) {
                ZqStandardhalf::updateOrCreate(['ScheduleID' => $v["ScheduleID"],'CompanyID' => $v["CompanyID"]] , $v);
            }
            
        }
    }
}