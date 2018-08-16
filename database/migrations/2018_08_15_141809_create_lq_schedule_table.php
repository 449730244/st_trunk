<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLqScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lq_schedule', function (Blueprint $table) {
            $table->increments('ScheduleID')->unique()->comment('比赛ID');
            $table->integer('SClassID')->nullable()->comment('赛事ID');
            $table->string('SClassType')->nullable()->comment('赛事类型');
            $table->string('SClassName_J')->nullable()->comment('赛事类型简体名称');
            $table->string('SClassName_F')->nullable()->comment('赛事类型繁体名称');
            $table->string('MatchNumber')->nullable()->comment('比赛节数：2:上下半场，4：分4小节');
            $table->string('Color')->nullable()->comment('颜色值');
            $table->string('MatchTime')->nullable()->comment('开赛时间');
            $table->string('MatchState')->nullable()->comment('比赛状态：-1:完场, -2:待定,-3:中断,-4:取消,-5:推迟,50中场');
            $table->string('MatchNumberTime')->nullable()->comment('比赛剩余时间');
            $table->string('HomeTeamID')->nullable()->comment('主队ID');
            $table->string('HomeTeamName_J')->nullable()->comment('主队简体名');
            $table->string('HomeTeamName_F')->nullable()->comment('主队繁体名');
            $table->string('GuestTeamID')->nullable()->comment('客队ID');
            $table->string('GuestTeamName_J')->nullable()->comment('客队简体名');
            $table->string('GuestTeamName_F')->nullable()->comment('客队繁体名');
            $table->string('HomeTeamRank')->nullable()->comment('主队排名');
            $table->string('GuestTeamRank')->nullable()->comment('客队排名');
            $table->string('HomeTeamScore')->nullable()->comment('主队得分');
            $table->string('GuestTeamScore')->nullable()->comment('客队得分');
            $table->string('HomeOneScore')->nullable()->comment('主队一节得分');
            $table->string('HomeTwoScore')->nullable()->comment('主队二节得分');
            $table->string('HomeThreeScore')->nullable()->comment('主队三节得分');
            $table->string('HomeFourScore')->nullable()->comment('主队四节得分');
            $table->string('GuestOneScore')->nullable()->comment('客队一节得分');
            $table->string('GuestTwoScore')->nullable()->comment('客队二节得分');
            $table->string('GuestThreeScore')->nullable()->comment('客队三节得分');
            $table->string('GuestFourScore')->nullable()->comment('客队四节得分');
            $table->string('OverTimeNumber')->nullable()->comment('加时数 ，即几个加时');
            $table->string('HomeOneOverTimeScore')->nullable()->comment('主队1ot得分');
            $table->string('HomeTwoOverTimeScore')->nullable()->comment('主队2ot得分');
            $table->string('HomeThreeOverTimeScore')->nullable()->comment('主队3ot得分');
            $table->string('GuestOneOverTimeScore')->nullable()->comment('客队1ot得分');
            $table->string('GuestTwoOverTimeScore')->nullable()->comment('客队2ot得分');
            $table->string('GuestThreeOverTimeScore')->nullable()->comment('客队3ot得分');
            $table->string('TechnicalStatistics')->nullable()->comment('是否有技术统计');
            $table->string('TVShow')->nullable()->comment('电视直播');
            $table->string('TVRemark')->nullable()->comment('备注，直播内容');
            $table->string('Neutral')->nullable()->comment('中立场');
            $table->string('Season')->nullable()->comment('赛季');
            $table->string('MatchAddress')->nullable()->comment('比赛场所');
            $table->string('MatchType')->nullable()->comment('比赛类型：如季前赛，常规赛，季后赛');
            $table->string('MatchCate')->nullable()->comment('比赛分类：例如第一圈，只有杯赛或季后赛才有数据');
            $table->string('MatchSubSClass')->nullable()->comment('比赛子分类：例如A组，只有杯赛才有数据');
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
        Schema::dropIfExists('lq_schedule');
    }
}
