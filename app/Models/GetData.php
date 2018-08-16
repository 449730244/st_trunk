<?php
namespace App\Models;

use GuzzleHttp\Client;
use Cache;

class GetData
{
    protected $client;
    const TOKEN = '12312313123';

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
    }

    /**
     * 获取联赛的赛程
     * @param $sclassid 联赛id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function BF_XML($sclassid){
        $sclass = ZqSclass::find($sclassid);
        if (!$sclass){
            return '足球分类不存在';
        }
        $response = $this->client->request('get','zq/BF_XML.aspx',[
            'query' => ['token' => self::TOKEN, 'sclassid'=>$sclassid]
        ]);

        //是杯赛还是联赛，杯赛没有轮数，也没有子赛
        $booleanType = false; //默认是杯赛
        if ($sclass->Type == 1){ //联赛
            $booleanType = true;
        }
        $content = $response->getBody();
        $obj = json_decode($content);
        $absContent = json_encode($obj,  JSON_UNESCAPED_UNICODE); //原始数据
        Try{
            if (is_array($obj->data) && empty($obj->data)){
                throw new \Exception("没有得到数据!");
            }

            $match = $obj->data->match;
            if (!is_array($match) && is_object($match)){
                $match = [$match];
            }

            $matchObjs = [];
            foreach ($match as $item){
                $matchObj = new \stdClass();
                $matchObj->ScheduleID = (int)$item->a;
                $matchObj->SclassID = (int)$sclassid;
                $matchObj->Color = $item->b;
                $matchObj->MatchSeason = $item->x;
                if ($booleanType){
                    $matchObj->Round = (int)$item->s;
                    $matchObj->Grouping = "";
                }else{
                    $matchObj->Round = 0;
                    $matchObj->Grouping = $item->s;
                }
                $home = $item->h; //主队
                $guest = $item->i; //客队
                if ($home){
                    $homeArr = explode(',', $home);
                    $matchObj->HomeTeamID = $homeArr[3];
                    $matchObj->HomeTeam = $homeArr[0];
                }
                if ($guest){
                    $guestArr = explode(',', $guest);
                    $matchObj->GuestTeamID = $guestArr[3];
                    $matchObj->GuestTeam = $guestArr[0];
                }
                $matchObj->Neutrality = $item->z == 'false' ? false : true;
                $matchObj->MatchTime = $item->d;
                $matchObj->MatchTime2 = null;
                $matchObj->Location = is_array($item->t) ? implode('|', $item->t) : $item->t;
                $matchObj->Home_order = (int)$item->p;
                $matchObj->Guest_order = (int)$item->q;
                $matchObj->MatchState = (int)$item->f;
                $matchObj->WeatherIcon = is_array($item->u) ? implode('|', $item->u) : $item->u;
                $matchObj->Weather = is_array($item->v) ? implode('|', $item->v) : $item->v;
                $matchObj->Temperature = is_array($item->w) ? implode('|', $item->w) : $item->w;
                $matchObj->TV = null;
                $matchObj->Umpire = null;
                $matchObj->Visitor = null;
                $matchObj->HomeScore = (int)$item->j;
                $matchObj->GuestScore = (int)$item->k;
                $matchObj->HomeHalfScore = (int)$item->l;
                $matchObj->GuestHafeScore = (int)$item->m;
                if (isset($item->explain2)){
                    $matchObj->Explain = is_array($item->explain2) ? implode('|', $item->explain2) : $item->explain2;
                }else{
                    $matchObj->Explain = null;
                }

                $matchObj->Home_Red = (int)$item->n;
                $matchObj->Guest_Red = (int)$item->o;
                if ($item->yellow){
                    $yellowArr = explode('-', $item->yellow);
                    $matchObj->Home_Yellow = isset($yellowArr[0]) ? (int)$yellowArr[0] : 0;
                    $matchObj->Guest_Yellow = isset($yellowArr[1]) ? (int)$yellowArr[1] : 0;
                }else{
                    $matchObj->Home_Yellow = 0;
                    $matchObj->Guest_Yellow = 0;
                }
                $matchObj->bf_changetime = "";
                $matchObj->grouping2 = is_array($item->y) ? implode('|', $item->y) : $item->y;
                $matchObj->subSclassID = is_array($item->subID) ? null : (int)$item->subID;
                $matchObjs[] = [
                    'id'=> $matchObj->ScheduleID,
                    'data'=>$matchObj
                ];
            }
            array_multisort(array_column($matchObjs,'id'),SORT_ASC, $matchObjs);
            Cache::store('file')->forever('BF_XML_'.$sclassid, $matchObjs);
            return 'success';
        }catch (\Exception $exception){
            return "赛事ID{$sclassid}异常".$exception->getMessage()."<br>获取的原始数据为:".$absContent;
        }
    }

    /**
     * 赛程数据写入数据库
     * @param $sclassid
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function BF_XML_to_database($sclassid){

        $sclass = ZqSclass::find($sclassid);
        if (!$sclass){
            return '足球分类不存在';
        }
        if (!Cache::store('file')->has('BF_XML_'.$sclassid)){
            return "缓存里没有数据";
        }
        $data = Cache::store('file')->get('BF_XML_'.$sclassid);
        Try{
            \DB::transaction(function() use ($data){
                foreach ($data as $item){
                    $item = $item['data'];
                    $ZqSchedule  = ZqSchedule::updateOrCreate(
                        ['ScheduleID'=>$item->ScheduleID],
                        [
                            'SclassID' => $item->SclassID,
                            'MatchSeason' => $item->MatchSeason,
                            'Round' => $item->Round,
                            'Grouping' => is_array($item->Grouping)?"":trim($item->Grouping),
                            'HomeTeamID' => $item->HomeTeamID,
                            'GuestTeamID' => $item->GuestTeamID,
                            'HomeTeam' => $item->HomeTeam,
                            'GuestTeam' => $item->GuestTeam,
                            'Neutrality' => $item->Neutrality,
                            'MatchTime' => $item->MatchTime,
                            'MatchTime2' => $item->MatchTime2,
                            'Location' => $item->Location,
                            'Home_order' => $item->Home_order,
                            'Guest_order' => $item->Guest_order,
                            'MatchState' => $item->MatchState,
                            'WeatherIcon' => $item->WeatherIcon,
                            'Weather' => $item->Weather,
                            'Temperature' => $item->Temperature,
                            'HomeScore' => $item->HomeScore,
                            'GuestScore' => $item->GuestScore,
                            'HomeHalfScore' => $item->HomeHalfScore,
                            'GuestHafeScore' => $item->GuestHafeScore,
                            'Explain' => $item->Explain,
                            'Home_Red' => $item->Home_Red,
                            'Guest_Red' => $item->Guest_Red,
                            'Home_Yellow' => $item->Home_Yellow,
                            'Guest_Yellow' => $item->Guest_Yellow,
                            'grouping2' => $item->grouping2,
                            'subSclassID' => $item->subSclassID,
                        ]
                    );
                }
            });
            return 'success';
        }catch (\Exception $exception){
            return "赛事ID{$sclassid}写入数据库失败：".$exception->getMessage();
        }
    }




}