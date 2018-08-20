<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\ZqLetgoal;
use App\Models\ZqLetgoalhalf;
use App\Models\ZqStandard;
use App\Models\ZqStandardhalf;
use App\Models\ZqTotalscore;
use App\Models\ZqTotalscorehalf;

use App\Models\LqLetgoal;
use App\Models\LqTotalscore;
use App\Models\LqEuropeodds;
use App\Models\LqLetgoalhalf;
use App\Models\LqTotalscorehalf;

class GetLetgoalDataController extends Controller
{
    protected $client;
    const TOKEN = '12312313123';

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://sapi.meme100.com/']);
    }
    /**
     * 足球让球赔率
     *
     */

    public function get_letgoal_data(){
        $model = new ZqLetgoal();
        $re = $model->get_letgoal_data();
        echo $re;
        
    }
    /**
     * 足球让分半场赔率
     *
     */
    public function get_halfletgoal_data(){
        $model = new ZqLetgoalhalf();
        $re = $model->get_halfletgoal_data();
        echo $re;
        
    }
    /**
     * 欧赔赔率
     *
     */
    public function get_standard_data(){
        $model = new ZqStandard();
        $re = $model->get_standard_data();
        echo $re;
        
    }
    /**
     * 半场欧赔赔率
     *
     */
    public function get_standardhalf_data(){
        $model = new ZqStandardhalf();
        $re = $model->get_standardhalf_data();
        echo $re;
        
    }
    /**
     * 足球大小球赔率
     *
     */
    public function get_totalscore_data(){
        $model = new ZqTotalscore();
        $re = $model->get_totalscore_data();
        echo $re;
        
    }
    /**
     * 足球半场大小球赔率
     *
     */
    public function get_totalscorehalf_data(){
        $model = new ZqTotalscorehalf();
        $re = $model->get_totalscorehalf_data();
        echo $re;
        
    }

    /**
     * 篮球让球赔率（让分赔率）
     */
    public function get_lq_letgoal_data(){
        $model = new LqLetgoal();
        $re = $model->get_lq_letgoal_data();
        echo $re;
        
    }
    /**
     * 篮球大小球赔率（大小球）
     * 示例：116185,12,125.5,0.909,0.909,125,0.909,0.909,125.5,0.909,0.909,125,0.909,0.909
      比赛ID
      公司ID
      第1节盘口
      第1节大分赔率
      第1节小分赔率
      第2节盘口
      第2节大分赔率
      第2节小分赔率
      第3节盘口
      第3节大分赔率
      第3节小分赔率
      第4节盘口
      第4节大分赔率
      第4节小分赔率
     * 
     *
     */
    public function get_lq_totalscore_data(){
        $model = new LqTotalscore();
        $re = $model->get_lq_totalscore_data();
        echo $re;
        
    }
      /**
     * 篮球欧赔赔率
     * 示例：116185,12,125.5,0.909,0.909,125,0.909,0.909,125.5,0.909,0.909,125,0.909,0.909
      比赛ID
      公司ID
      第1节盘口
      第1节大分赔率
      第1节小分赔率
      第2节盘口
      第2节大分赔率
      第2节小分赔率
      第3节盘口
      第3节大分赔率
      第3节小分赔率
      第4节盘口
      第4节大分赔率
      第4节小分赔率

              <h>
<id>比赛ID</id>
<time>比赛时间</time>
<league>联赛英文名,繁体名,简体名</league>
<home>主队英文名,繁体名,简体名</home>
<away>客队英文名,繁体名,简体名</away>
<odds>
<o>博彩公司ID,博彩公司名,初盘主胜,初盘客胜,主胜,客胜,变化时间,赔率ID</o>
<o>博彩公司ID,博彩公司名,初盘主胜,初盘客胜,主胜,客胜,变化时间,赔率ID</o>
<o>博彩公司ID,博彩公司名,初盘主胜,初盘客胜,主胜,客胜,变化时间,赔率ID</o>
……………
</odds>
</h>
胜率，返回率计算方法：
主队胜率=(float)Math.Round(1/(1+主胜赔率/客胜赔率)*100,2);
客队胜率=(float)Math.Round(1/(1+客胜赔率/主胜赔率)*100,2);
返 回 率=(float)Math.Round(主胜胜率*主队赔率,2);
凯利指数：1.52*57.13%=0.925   2.15*42.87%=0.922
     * 
     *
     */
    public function get_lq_europeodds_data(){
        $model = new LqEuropeodds();
        $re = $model->get_lq_europeodds_data();
        echo $re;
    }
    /**
     * 篮球半球让球赔率
     * 示例：114908,2,1.5,0.87,0.87,1.5,0.87,0.87;
      比赛ID
      公司ID
      初盘盘口 （第一个赔率）
      主队初盘赔率
      客队初盘赔率
      即时盘口（当前赔率，不包括走地）
      主队即时赔率
      客队即时赔率
     * 
     *
     */
    public function get_lq_letgoalhalf_data(){
        $model = new LqLetgoalhalf();
        $re = $model->get_lq_letgoalhalf_data();
        echo $re;
    }
        /**
     * 篮球大小球让球赔率
     * 示例：116185,12,125.5,0.909,0.909,125,0.909,0.909
      比赛ID
      公司ID
      初盘盘口（第一个赔率）
      初盘大分赔率
      初盘小分赔率
      即时盘盘口（当前赔率，不包括走地）
      即时盘大分赔率
      即时盘小分赔率
     * 
     *
     */
    public function get_lq_totalscorehalf_data(){
        $model = new LqTotalscorehalf();
        $re = $model->get_lq_totalscorehalf_data();
        echo $re;
    }
}
