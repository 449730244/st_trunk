<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Support\Facades\Log;

class LqSclass extends Model
{
    //
    protected $table = 'lq_sclass';
    protected $guarded=[];
    protected $primaryKey = 'SClassID';

    public function getSclass()
    {
        Log::useFiles(storage_path('logs/lqSClass.log'));
        $client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
        $response = $client->request('get','lq/LqLeague_Xml.aspx', [
            'query' => ['token'=>'12312313123']
        ]);
        $content = $response->getBody();
        $data = json_decode($content);
        $match = $data->data->match;
        $sclass =[];
        Try{
            foreach ($match as $key => $item)
            {
                //赛事数据
                $sclass[$item->countryID]['SClass'][$key]['SClassID'] = $item->id;
                $sclass[$item->countryID]['SClass'][$key]['Color'] = $item->color;
                $sclass[$item->countryID]['SClass'][$key]['Name_JS'] = $item->short;
                $sclass[$item->countryID]['SClass'][$key]['Name_J'] = $item->gb;
                $sclass[$item->countryID]['SClass'][$key]['Name_F'] = $item->big;
                $sclass[$item->countryID]['SClass'][$key]['Name_E'] = $item->en;
                $sclass[$item->countryID]['SClass'][$key]['type'] = $item->type;
                $sclass[$item->countryID]['SClass'][$key]['Curr_matchSeason'] = $item->Curr_matchSeason;
                $sclass[$item->countryID]['SClass'][$key]['countryID'] = $item->countryID;
                $sclass[$item->countryID]['SClass'][$key]['country'] = $item->country;
                $sclass[$item->countryID]['SClass'][$key]['curr_year'] = $this->checkEmpty($item->curr_year);
                $sclass[$item->countryID]['SClass'][$key]['curr_month'] = $this->checkEmpty($item->curr_month);
                $sclass[$item->countryID]['SClass'][$key]['sclass_kind'] = $this->checkEmpty($item->sclass_kind);
                $sclass[$item->countryID]['SClass'][$key]['sclass_time'] = $this->checkEmpty($item->sclass_time);
                //国家数据
                $sclass[$item->countryID]['InfoID'] = $item->countryID;
                $sclass[$item->countryID]['NameCN'] = $item->country;
                Log::info("获取赛程ID：-->".$item->id."数据成功\n");
                echo "获取赛程ID：-->".$item->id."数据成功\n";
                //sleep(5);
            }
            $collection = collect($sclass);
            $sorted = $collection->sortBy('InfoID');
            Cache::store('file')->forever('SClass_list', $sorted);

        }catch (\Exception $exception){
            Log::info("获取赛事数据异常".$exception->getMessage()."<br>获取的原始数据为:".json_encode($data,JSON_UNESCAPED_UNICODE));
        }
    }

    public function getSClassInfo()
    {
        $content = file_get_contents('http://120.198.143.213:8072/phone/LqInfoIndex.aspx?lang=0&apiversion=1&from=2');
        $list = explode('$$',$content);
        $country = explode('!',$list[0]);
        $SClass = explode('!',$list[1]);
        $sclassArr =[];
        foreach ($country as $k => $val)
        {
            $countryArr = explode('^',$val);
            $sclassArr[$k]['countryID'] = $countryArr[0];
            $sclassArr[$k]['countryName'] = $countryArr[1];
            foreach ($SClass as $key => $item)
            {
               $arr = explode('^',$item);
               if($countryArr[0] == $arr[1])
                {
                    $sclassArr[$k]['SClass'][$key]['SClassID'] = $arr[0];
                    $sclassArr[$k]['SClass'][$key]['countryID'] = $arr[1];
                    $sclassArr[$k]['SClass'][$key]['Name_J'] = $arr[2];
                    $sclassArr[$k]['SClass'][$key]['Name_JS'] = $arr[3];
                    $sclassArr[$k]['SClass'][$key]['sclass_kind'] = $arr[4];
                    $sclassArr[$k]['SClass'][$key]['All_matchSeason'] = $arr[5];
                }
            }
        }
        $collection = collect($sclassArr);
        $sorted = $collection->sortBy('countryID');
        Cache::store('file')->forever('SClassInfo', $sorted);
    }

    public function checkEmpty($value)
    {
        return !empty($value) ? (is_array($value) || is_object($value) ? json_encode($value) : trim($value)) : null;
    }

}
