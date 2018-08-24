<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'HomeController@test')->name('test');
Route::get('/odds/allball', 'GetLetgoalDataController@get_letgoal_data'); //获取赔率信息写入数据表中
Route::get('/odds/halfball', 'GetLetgoalDataController@get_halfletgoal_data'); //获取半场赔率信息写入数据表中
Route::get('/odds/standard', 'GetLetgoalDataController@get_standard_data'); //欧赔
Route::get('/odds/standardDetail', 'GetLetgoalDataController@get_standard_detail_data'); //欧赔详情
Route::get('/odds/standardhalf', 'GetLetgoalDataController@get_standardhalf_data'); //半场欧赔
Route::get('/odds/totalscore', 'GetLetgoalDataController@get_totalscore_data'); //大小球
Route::get('/odds/totalscorehalf', 'GetLetgoalDataController@get_totalscorehalf_data'); //半场大小球
Route::get('/lqodds/allball', 'GetLetgoalDataController@get_lq_letgoal_data');//获取让分赔率
Route::get('/lqodds/totalscore', 'GetLetgoalDataController@get_lq_totalscore_data');//大小球
Route::get('/lqodds/europeodds', 'GetLetgoalDataController@get_lq_europeodds_data');//篮球欧赔
Route::get('/lqodds/europeoddsDetail', 'GetLetgoalDataController@get_lq_europeodds_detail_data');//篮球欧赔详情
Route::get('/lqodds/letgoalhalf', 'GetLetgoalDataController@get_lq_letgoalhalf_data');//半球让球
Route::get('/lqodds/totalscorehalf', 'GetLetgoalDataController@get_lq_totalscorehalf_data');//半球大小球

Route::get('test0', 'GetIndexInfoContorller@test')->name('test');
Route::get('getcountry', 'GetIndexInfoContorller@getCountryInfo')->name('getcountry');//获取国家列表
Route::get('getteaminfo', 'GetIndexInfoContorller@getFootBallTeamInfo')->name('getteaminfo');//获取球队信息
Route::get('getplayersinfo', 'GetIndexInfoContorller@getFootBallPlayerInfo')->name('getplayersinfo');//获取球员信息
Route::get('fillplayers', 'GetIndexInfoContorller@fillFootBallPlayer')->name('fillplayers');
Route::get('integralstatistic', 'GetIndexInfoContorller@integralStatistic')->name('integralstatistic');
Route::get('topscorer', 'GetIndexInfoContorller@TopScorer')->name('topscorer');
Route::get('fillscore', 'GetIndexInfoContorller@fillZqScore')->name('fillscore');
Route::get('filltopscorer', 'GetIndexInfoContorller@fillTopScorer')->name('filltopscorer');

Route::get('filldata',function (){return view('getdata');});//前端数据填充

Route::get('getdata', 'GetIndexInfoContorller@getdata');//定时任务数据填充




Route::middleware(['auth'])->group(function () {

    Route::get('/get/League_xml', 'GetDataController@League_xml'); //从接口获取数据
    Route::get('/set/League_xml', 'SetDataController@League_xml'); //把缓存数据写入数据库

    Route::get('/get/BF_XML', 'GetDataController@BF_XML'); //获取赛程数据
    Route::get('/get/paginate_BF_XML', 'GetDataController@paginate_BF_XML')->name('paginate_BF_XML'); //分页获取赛程数据
    Route::get('/set/BF_XML', 'SetDataController@BF_XML'); //赛程数据入库

    Route::get('/get/SubLeague_XML', 'GetDataController@SubLeague_XML'); //获取子联赛数据
    Route::get('/set/SubLeague_XML', 'SetDataController@SubLeague_XML'); //子联赛数据入库

    Route::get('/get/odds', 'GetDataController@odds');//获取赔率

});
Route::get('/get/lineup', 'HomeController@lineup'); //
Route::get('/set/lineup', 'SetDataController@lineup'); //把缓存数据写入数据库

Route::get('/set/PlayerTech', 'SetDataController@PlayerTech'); //把缓存数据写入数据库
Route::get('/get/playerTech', 'HomeController@playerTech'); //
Route::get('/set/lqSclass', 'SetDataController@lqSClass'); //

Route::get('/get/getLqSclass', 'HomeController@getLqSclass'); //
Route::get('/get/getLqSchedule', 'HomeController@getLqSchedule'); //

Route::get('/set/lqSchedule', 'SetDataController@set_lqSchedule'); //
Route::get('/set/todayLqSchedule', 'SetDataController@set_today_lqSchedule'); //
Route::get('/set/SetLqSClass', 'SetDataController@SetLqSClass'); //
Route::get('/set/set_lqSchedule', 'SetDataController@set_lqSchedule'); //

Route::get('getSportHome', 'GetSportController@getSportHome'); //
Route::get('setSportData', 'GetSportController@setSportData'); //

Route::get('getLeftData', 'GetSportController@getLeftData'); //
Route::get('getLqSportHome', 'GetSportController@getLqSportHome'); //

Route::get('getZqPlayerTech', 'GetSportController@getZqPlayerTech');