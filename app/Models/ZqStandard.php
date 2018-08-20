<?php

namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class ZqStandard extends Model
{
    protected $table='zq_standard';
    protected $primaryKey='Indentity';
    protected $guarded = [];

    public function get_standard_data(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/odds.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $obj = json_decode($content);
        $data = $obj->data;
        $data = base64_decode($data);
        $ar = explode('$',$data);
        $ou_ret = explode(';',$ar['3']);//欧赔
        if (!empty($ou_ret)) {
            foreach ($ou_ret as $k => $va) {
                $ou_dar = explode(',',$va);
                $ou_data[]=array(
                    "list"=>array(
                          "ScheduleID"=>$ou_dar['0'],
                          "CompanyID"=>$ou_dar['1'],
                          "FirstHomeWin"=>floatval($ou_dar['2']),
                          "FirstStandoff"=>floatval($ou_dar['3']),
                          "FirstGuestWin"=>floatval($ou_dar['4']),
                          // "HomeWin"=>'',
                          // "Standoff"=>'',
                          // "GuestWin"=>'',
                          "HomeWin_R"=>floatval($ou_dar['5']),
                          "GuestWin_R"=>floatval($ou_dar['7']),
                          "Standoff_R"=>floatval($ou_dar['6']),
                          // "Result"=>'',
                          // "ClosePan"=>'',
                          // "ModifyTime"=>'',
                    ),
              
                );
                
            }
        }else{
            $ou_data = array();
        }
        if (!empty($ou_data)) {
            foreach ($ou_data as $k => $v) {
                ZqStandard::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }
            
        }
    }
}

