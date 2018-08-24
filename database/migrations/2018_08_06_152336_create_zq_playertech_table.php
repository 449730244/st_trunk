<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZqPlayertechTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zq_playertech', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identity')->nullable();
            $table->integer('PlayerID')->nullable()->comment('球员ID');
            $table->string('Name_J')->nullable()->comment('球员名字简体');
            $table->string('Name_F')->nullable()->comment('球员名字繁体');
            $table->integer('SchSum')->nullable()->comment('出场');
            $table->integer('BackSum')->nullable()->comment('替补');
            $table->integer('PlayingTime')->nullable()->comment('出场时间');
            $table->integer('notPenaltyGoals')->nullable()->comment('常规进球数');
            $table->integer('penaltyGoals')->nullable()->comment('点球进球数');
            $table->integer('shots')->nullable()->comment('射门次数');
            $table->integer('shotsTarget')->nullable()->comment('射正次数');
            $table->integer('wasFouled')->nullable()->comment('被侵犯');
            $table->integer('bestSum')->nullable()->comment('最佳');
            $table->double('rating',10,2)->nullable()->comment('评分');
            $table->integer('effRating')->nullable();
            $table->integer('totalPass')->nullable()->comment('总传球数');
            $table->integer('passSuccess')->nullable()->comment('传球成功数');
            $table->integer('keyPass')->nullable()->comment('关键传球');
            $table->integer('assist')->nullable()->comment('助攻');
            $table->integer('longBalls')->nullable()->comment('长传');
            $table->integer('longBallsSuc')->nullable()->comment('长传成功');
            $table->integer('throughBall')->nullable()->comment('直塞');
            $table->integer('throughBallSuc')->nullable()->comment('直塞成功');
            $table->integer('Cross')->nullable()->comment('横传');
            $table->integer('CrossSuc')->nullable()->comment('横传成功');
            $table->integer('dribblesSuc')->nullable()->comment('带球摆脱');
            $table->integer('offside')->nullable()->comment('越位');
            $table->integer('tackle')->nullable()->comment('铲断');
            $table->integer('interception')->nullable()->comment('拦截');
            $table->integer('clearances')->nullable()->comment('解围');
            $table->integer('clearanceWon')->nullable()->comment('解围成功');
            $table->integer('dispossessed')->nullable()->comment('偷球');
            $table->integer('shotsBlocked')->nullable()->comment('封堵');
            $table->integer('aerialSuc')->nullable()->comment('头球');
            $table->integer('fouls')->nullable()->comment('犯规');
            $table->integer('red')->nullable()->comment('红牌');
            $table->integer('yellow')->nullable()->comment('黄牌');
            $table->integer('ownGoal')->nullable()->comment('乌龙球数');
            $table->integer('touch')->nullable()->comment('手球');
            $table->integer('turnOver')->nullable()->comment('失球数');
            $table->integer('penaltyProvoked')->nullable()->comment('罚球');
            $table->integer('shotsOnPost')->nullable()->comment('射中门柱次数');
            $table->integer('Goals')->nullable()->comment('进球');
            $table->double('passSucPercent',10,2)->nullable()->comment('传球成功率');
            $table->integer('assistMinute')->nullable()->comment('分钟/助攻');
            $table->integer('goalMinute')->nullable()->comment('分钟/球');
            $table->integer('goalPercent')->nullable()->comment('入球转化率');
            $table->string('type')->nullable()->comment('类型：home：主，guest：客 total：全');
            $table->integer('SClassID')->nullable()->comment('赛事');
            $table->integer('TeamID')->nullable()->comment('所属队伍id');
            $table->integer('MatchSeason')->nullable()->comment('赛季');
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
        Schema::dropIfExists('zq_playertech');
    }
}
