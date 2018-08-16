<?php

namespace App\Http\Controllers;

use App\Models\ZqSchedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Cache;
use App\Models\ZqSclass;
use App\Models\GetData;
use App\Models\ZqStandard;


class GetDataController extends Controller
{
    protected $client;
    const TOKEN = '12312313123';

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
    }

    /**
     * 联赛杯赛
     *
     */
    public function League_xml(){
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('GET', 'zq/League_XML.aspx', [
            'query' => ['token'=>'12312313123']
        ]);

        $content = $response->getBody();
        $obj = json_decode($content);
        $match = $obj->data->match;
        $str = [];
        foreach ($match as $item){
            $str[$item->country]['id'] = $item->countryID;
            $str[$item->country]['country'] = $item->country;
            $str[$item->country]['countryEn'] = $item->countryEn;
            $str[$item->country]['countryLogo'] = is_array($item->countryLogo) ? implode('|', $item->countryLogo): $item->countryLogo;
            $str[$item->country]['areaID'] = (int)$item->areaID;
            $str[$item->country]['events'][$item->gb_short] =[
                'id' => $item->id,
                'logo'=> $item->logo,
                'color' => $item->color,
                'gb_short' => $item->gb_short,
                'big_short' => $item->big_short,
                'en_short' => $item->en_short,
                'gb' => $item->gb,
                'big' => $item->big,
                'en' => $item->en,
                'type' => (int)$item->type,
                'SubSclass' => (int)$item->type == 1 ? '联赛' : '杯赛',
                'Curr_matchSeason' => is_array($item->Curr_matchSeason) ? implode('|', $item->Curr_matchSeason) : $item->Curr_matchSeason,
                'areaID' => $item->areaID,
                'sum_round' => $item->type == 1 ? (int)$item->sum_round : 0,
                'curr_round' => $item->type == 1 ? (int)$item->curr_round : 0,
            ];
        }

        $collection = collect($str);
        $sorted = $collection->sortBy('id');
        Cache::store('file')->forever('League_xml', $sorted);
        return $sorted;
    }

    public function BF_XML(Request $request){
        $sclassid = $request->input('sclassid');
        $GetData = new GetData();
        return $GetData->BF_XML($sclassid);
    }

    /**
     * 子联赛
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function SubLeague_XML(){
        $response = $this->client->request('get','zq/SubLeague_XML.aspx',[
            'query' => ['token' => self::TOKEN]
        ]);

        $content = $response->getBody();
        $obj = json_decode($content);
        $items = $obj->data->Item;
        $subObjs = [];
        foreach ($items as $k => $item){
            $subobj = new \stdClass();
            $subobj->SubSclassID = (int)$item->subID;
            $subobj->SclassID = (int)$item->id;
            $subobj->IsHaveScore = $item->IsHaveScore;
            $subobj->sortNumber = is_array($item->Num) ? null : (int)$item->Num;
            $subobj->Curr_round = is_array($item->curr_round) ? null : (int)$item->curr_round;
            $subobj->Count_round = is_array($item->sum_round) ? null : (int)$item->sum_round;
            $subobj->IsCurrentSclass = $item->IsCurrentSclass;
            $subobj->subSclassName = $item->Name;
            $subobj->includeSeason = is_array($item->IncludeSeason) ? null : $item->IncludeSeason;
            $subobj->IsZu = $item->IsZu;
            $subobj->groupNum = null;
            $subobj->MatchSeason = is_array($item->CurrentSeason) ? null : $item->CurrentSeason;
            $subObjs[] = [
                'id' => (int)$item->subID,
                'data' => $subobj,
            ];
        }
        array_multisort(array_column($subObjs,'id'),SORT_ASC, $subObjs);
        Cache::store('file')->forever('SubLeague_XML', $subObjs);

        return $subObjs;
    }

    public function getPlayTechPage()
    {
        return ZqSchedule::paginate(20)->toArray();
    }


    public function odds(){
        $response = $this->client->request('get','zq/odds.aspx',[
            'query' => ['token' => self::TOKEN]
        ]);

        $data = $response->getBody();
        $dataList = json_decode($data);
        $data = base64_decode($dataList->data);
        $data_arr = explode("$", $data);
        if (count($data_arr) != 8){ //接口返回数据是8部份
            return "返回数据错误，不足8部份";
        }
        $sclasses =  isset($data_arr[0])? $data_arr[0] :""; //联赛资料
        $schedules = isset($data_arr[1])? $data_arr[1] :""; //赛程资料
        $yapei = isset($data_arr[2])? $data_arr[2] :"";  //亚赔（让球盘）
        $oupei = isset($data_arr[3]) ? $data_arr[3] : ""; //欧赔（标准盘）
        $bs = isset($data_arr[4])? $data_arr[4] :""; //大小球
        $ban =  isset($data_arr[6])? $data_arr[6] :""; //半场让球
        $bandx = isset($data_arr[7])? $data_arr[7] :""; //半场大小球
        Log::useFiles(storage_path('logs/odds.log'));
        //未完待续
        if ($oupei){

            $arr = explode(';', $oupei);
            foreach ($arr as $key => $item){
                $itemArr = explode(',', $item);
                if (count($itemArr) == 8){
                    ZqStandard::create([
                        'ScheduleID' => $itemArr[0],
                        'CompanyID' => $itemArr[1],
                        'FirstHomeWin' => $itemArr[2],
                        'FirstStandoff' => $itemArr[3],
                        'FirstGuestWin' => $itemArr[4],
                        'HomeWin' => "",
                        'Standoff' => "",
                        'GuestWin' => "",
                        'HomeWin_R' => $itemArr[5],
                        'GuestWin_R' => $itemArr[7],
                        'Standoff_R' => $itemArr[6],
                    ]);

                }else{
                    Log::error('数据结构错误的项目：'.$item);
                }
            }

        }

    }


}
