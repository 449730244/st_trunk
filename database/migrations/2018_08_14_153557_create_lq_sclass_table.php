<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLqSclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lq_sclass', function (Blueprint $table) {
            $table->increments('SClassID')->unique()->comment('赛事ID');
            $table->char('Color')->nullable()->comment('颜色值');
            $table->string('Name_JS')->nullable()->comment('简体简称');
            $table->string('Name_J')->nullable()->comment('简体全称');
            $table->string('Name_F')->nullable()->comment('繁体全称');
            $table->string('Name_E')->nullable()->comment('英文全称');
            $table->string('type')->nullable()->comment('比赛分几节');
            $table->string('Curr_matchSeason')->nullable()->comment('当前赛事');
            $table->integer('countryID')->nullable()->comment('国家ID');
            $table->string('country')->nullable()->comment('国家名');
            $table->integer('curr_year')->comment('当前年份');
            $table->integer('curr_month')->nullable()->comment('当前月份');
            $table->integer('sclass_kind')->nullable()->comment('类型，1联赛2杯赛');
            $table->string('sclass_time')->comment('1节打几分钟');
            $table->string('All_matchSeason')->comment('所有赛季时间');
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
        Schema::dropIfExists('lq_sclass');
    }
}
