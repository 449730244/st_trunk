<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings']
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        //用户登录
        $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');
        // 刷新token
        $api->put('authorizations/update', 'AuthorizationsController@update')->name('api.authorizations.update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')->name('api.user.me');
            //用户列表
            $api->get('users', 'UsersController@index')->name('api.user.index');
            //获取足球地区
            $api->get('zq/areas','ZqAreasController@index')->name('api.zqarea.index');
            //获取赛事
            $api->get('zqsclass/{country_id}', 'ZqSclassController@index')->name('api.zqSclass.index');
            //获取赛事下的赛季
            $api->get('zqsclasses/{sclass}/seasons', 'ZqMatchSeasonsController@index')->name('api.zqmatchseasons.index');

            //足球
            //球队信息
            $api->get('zq/teaminfo', 'ZqBaseDataInfoController@zqTeamInfo')->name('api.zqbasedatainfo.info');
            $api->get('zq/playerinfo', 'ZqBaseDataInfoController@playerinfo')->name('api.zqbasedatainfo.info');
            //射手榜
            $api->get('zq/topscorer','ZqBaseDataInfoController@zqTopScore')->name('api.zqbasedatainfo.topscorer');
            //国家列表
            $api->get('zq/countrylist','ZqBaseDataInfoController@countryList')->name('api.zqbasedatainfo.countrylist');
            //积分数据
            $api->get('zq/zqjifen','ZqBaseDataInfoController@zqJifen')->name('api.zqbasedatainfo.zqjifen');


            //获取赛事的赛程
            $api->get('zqsclasses/{sclass}/schedules/{matchSeason}/{subSclassID?}', 'ZqScheduleController@index')->name('api.zqSchedules.index');
            //获取子联赛
            $api->get('zqsclasses/{sclass}/subsclasses/{matchSeason}', 'ZqSubSclassController@index')->name('api.zqSubSclass.index');
            //获取某场比赛的球员数据
            $api->get('zqplayertech','ZqPlayerTechController@index')->name('api.zqplayertech.index');
            //获取比赛的阵容
            $api->get('zqlineup','ZqLineupController@index')->name('api.zqlineup.index');


            //赔率相关
            $api->get('zqLetgoal', 'ZqLetgoalController@index')->name('api.zqLetgoal.index');
            $api->get('/zqletgoalinfo/{ScheduleID}', 'ZqLetgoalController@zqletgoalinfo')->name('api.zqLetgoal.zqletgoalinfo');
            $api->get('/halfballinfo/{ScheduleID}', 'ZqLetgoalController@halfballinfo')->name('api.zqLetgoal.halfballinfo');

            $api->get('halfgoal', 'ZqLetgoalController@halfgoal')->name('api.zqLetgoal.halfgoal');
            $api->get('standard', 'ZqLetgoalController@standard')->name('api.zqLetgoal.standard');
            $api->get('/standardinfo/{ScheduleID}', 'ZqLetgoalController@standardinfo')->name('api.zqLetgoal.standardinfo');
            $api->get('standardhalf', 'ZqLetgoalController@standardhalf')->name('api.zqLetgoal.standardhalf');
            $api->get('totalscore', 'ZqLetgoalController@totalscore')->name('api.zqLetgoal.totalscore');
            $api->get('totalscorehalf', 'ZqLetgoalController@totalscorehalf')->name('api.zqLetgoal.totalscorehalf');

            $api->get('lqLetgoal', 'LqLetgoalController@index')->name('api.lqLetgoal.index');
            $api->get('lqtotalscore', 'LqLetgoalController@totalscore')->name('api.lqLetgoal.lqtotalscore');
            $api->get('lqeuropeodds', 'LqLetgoalController@europeodds')->name('api.lqLetgoal.lqeuropeodds');
            $api->get('lqeuropeoddsinfo/{ScheduleID}', 'LqLetgoalController@lqeuropeoddsinfo')->name('api.lqLetgoal.lqeuropeoddsinfo');
            $api->get('lqletgoalhalf', 'LqLetgoalController@letgoalhalf')->name('api.lqLetgoal.lqletgoalhalf');
            $api->get('lqtotalscorehalf', 'LqLetgoalController@totalscorehalf')->name('api.lqLetgoal.lqtotalscorehalf');


           //篮球基本数据
            $api->get('lq/teaminfo','lqBaseDataInfoController@teamInfo')->name('api.lqBaseDataInfo.lqteaminfo');
            $api->get('lq/playerinfo','lqBaseDataInfoController@playerInfo')->name('api.lqBaseDataInfo.playerinfo');
            $api->get('lq/jifen','lqBaseDataInfoController@score')->name('api.lqBaseDataInfo.score');
            $api->get('lq/teahniccount','lqBaseDataInfoController@teahnicCount')->name('api.lqBaseDataInfo.teahniccount');

            //篮球赛事列表
            $api->get('/lq/lqsclass/{country_id}','LqSclassController@index')->name('api.lqsclass.index');
            //篮球国家列表
            $api->get('/lq/lqSclassInfo','LqSclassInfoController@index')->name('api.lqsclassinfo.index');
            //篮球比赛
            $api->get('/lq/lqSchedule/{sclass_id}','LqScheduleController@index')->name('api.lqsclassinfo.index');
            
        });
    });
    $api->get('test9','lqBaseDataInfoController@teahnicCount')->name('api.lqBaseDataInfo.score');

});