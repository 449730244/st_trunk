<?php
namespace App\Models;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use DB;
class LqLetgoal extends Model
{
    protected $table    =   'lq_letgoal';

    protected  $primaryKey='OddsID';

    protected $fillable = [  
       "ScheduleID",
        "CompanyID",
        "HomeOdds",
        "LetGoal",
        "GuestOdds",
        "HomeOdds_F",
        "LetGoal_F",
        "GuestOdds_F",
        "ModifyTime",
        "HomeOdds_R",
        "Goal_R",
        "GuestOdds_R",
        "create_time",
        "update_time",
    ];
    public function get_lq_letgoal_data(){
        $ScheduleID_list=DB::table('lq_schedule')->get(['ScheduleID']);
        foreach ($ScheduleID_list as $key => $value) {
            $this->writedata($value->ScheduleID);
            if ($key>0 && $key%500 == 0) sleep(90);
        }
        echo '操作成功';
    }
    public function writedata($ScheduleID='0')
    {
	if($ScheduleID == '0') return '没有传入赛事id';
        $content = getCurl('http://120.198.143.213:8072/phone/LqHandicap2.aspx?ID='.$ScheduleID.'&lang=0');
        if (!empty($content)) {
            $list = explode('!',$content);
            $ud_data = array();
            foreach ($list as $k => $v) {
                $dar = explode('^',$v);
                $company_id = $this->get_company_id($dar['0']);
                $ud_data[]=array(
                    "list"=>array(
                        "ScheduleID"=>$ScheduleID,
                        "CompanyID"=>$company_id,
                        // "HomeOdds"=>'',
                        // "LetGoal"=>'',
                        // "GuestOdds"=>'',
                        "HomeOdds_F"=>$dar['2'],
                        "LetGoal_F"=>$dar['3'],
                        "GuestOdds_F"=>$dar['4'],
                        // "ModifyTime"=>'',
                        "HomeOdds_R"=>$dar['5'],
                        "Goal_R"=>$dar['6'],
                        "GuestOdds_R"=>$dar['7'],
                        // "create_time"=>'',
                        // "update_time"=>'',
                    )
                );
            }
            foreach ($ud_data as $k => $v) {
                LqLetgoal::updateOrCreate(['ScheduleID' => $v['list']["ScheduleID"],'CompanyID' => $v['list']["CompanyID"]] , $v['list']);
            }
        }
    }
    public function get_company_id($name){
        switch ($name) {
            case '澳门':
                $company_id = 1;
                break;
            case '易胜博':
                $company_id = 2;
                break;
            case '皇冠':
                $company_id = 3;
                break;
            case 'bet365':
                $company_id = 8;
                break;
            case '韦德':
                $company_id = 9;
                break;
            case '利记':
                $company_id = 31;
                break;
            // case '立博':
            //     $company_id = 4;
            //     break;
            // case 'SNAI':
            //     $company_id = 7;
            //     break;
            // case '威廉希尔':
            //     $company_id = 9;
            //     break;
            // case '明陞':
            //     $company_id = 17;
            //     break;
            // case 'Interwetten':
            //     $company_id = 19;
            //     break;
            // case '10BET':
            //     $company_id = 22;
            //     break;

            // case '金宝博':
            //     $company_id = 23;
            //     break;
            // case '12bet/沙巴':
            //     $company_id = 24;
            //     break;
            // case '盈禾':
            //     $company_id = 35;
            //     break;
            // case '18bet':
            //     $company_id = 42;
            //     break;
            // case 'ManbetX':
            //     $company_id = 45;
            //     break;
            default:
                $company_id = 0;
                break;
        }
        return $company_id;
    }


}