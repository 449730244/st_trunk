<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZqSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zq_schedule', function (Blueprint $table) {
            $table->increments('ScheduleID');
            $table->unsignedInteger('SclassID')->index()->comment('赛事类型编号');
            $table->string('MatchSeason')->nullable()->comment('赛季');
            $table->unsignedInteger('Round')->default(0)->nullable()->comment('轮次');
            $table->string('Grouping')->nullable()->comment('组');
            $table->unsignedInteger('HomeTeamID')->default(0)->nullable()->comment('主队ID');
            $table->unsignedInteger('GuestTeamID')->default(0)->nullable()->comment('客队ID');
            $table->string('HomeTeam')->nullable()->comment('主队名');
            $table->string('GuestTeam')->nullable()->comment('客队名');
            $table->boolean('Neutrality')->default(false)->comment('是否中立场');
            $table->dateTime('MatchTime')->comment('比赛时间');
            $table->dateTime('MatchTime2')->nullable()->comment('开下半场时间');
            $table->string('Location')->nullable()->comment('比赛地点');
            $table->string('Home_order')->nullable()->comment('主队排名');
            $table->string('Guest_order')->nullable()->comment('客队排名');
            $table->smallInteger('MatchState')->default(0)->comment('比赛状态 0：未开场，-1：完场');
            $table->string('WeatherIcon')->nullable()->comment('天气图标');
            $table->string('Weather')->nullable()->comment('天气');
            $table->string('Temperature')->nullable()->comment('温度');
            $table->string('TV')->nullable()->comment('电视台');
            $table->string('Umpire')->nullable()->comment('裁判');
            $table->unsignedInteger('Visitor')->nullable()->comment('阵容');
            $table->integer('HomeScore')->default(0)->comment('主得分');
            $table->integer('GuestScore')->default(0)->comment('客得分');
            $table->integer('HomeHalfScore')->default(0)->comment('主半得分');
            $table->integer('GuestHafeScore')->default(0)->comment('客半得分');
            $table->string('Explain')->nullable()->comment('比分说明或其它说明');
            $table->text('Explainlist')->nullable()->comment('后台选择控制的说明，是一串数据列。前台展示需解析');
            $table->integer('Home_Red')->default(0)->comment('主队红牌数');
            $table->integer('Guest_Red')->default(0)->comment('客队红牌数');
            $table->integer('Home_Yellow')->default(0)->comment('主队黄牌数');
            $table->integer('Guest_Yellow')->default(0)->comment('客队黄牌数');
            $table->dateTime('bf_changetime')->nullable()->comment('比分变化时间');
            $table->smallInteger('ShangPan')->nullable()->comment('上盘球队 1：主队是上盘2：客队是上盘');
            $table->string('grouping2')->nullable()->comment('下级分组  如：A B C D');
            $table->string('subSclassID')->nullable()->comment('子联赛ID');
            $table->boolean('bfShow')->default(true)->comment('是否显示');
            $table->unsignedInteger('homeCorner')->default(0)->comment('主队角球数');
            $table->unsignedInteger('homeCornerHalf')->default(0)->comment('主队半场角球数');
            $table->unsignedInteger('guestCorner')->default(0)->comment('客队角球数');
            $table->unsignedInteger('guestCornerHalf')->default(0)->comment('客队半场角球数');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zq_schedule');
    }
}
