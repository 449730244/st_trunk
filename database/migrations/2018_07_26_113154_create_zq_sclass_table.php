<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZqSclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zq_sclass', function (Blueprint $table) {
            $table->increments('SClassID')->unique();
            $table->char('Color')->nullable()->comment('颜色值');
            $table->string('Name_J')->nullable()->comment('简体全称');
            $table->string('Name_F')->nullable()->comment('繁体全称');
            $table->string('Name_E')->nullable()->comment('英文全称');
            $table->string('Name_JS')->nullable()->comment('简体简称');
            $table->string('Name_FS')->nullable()->comment('繁体简称');
            $table->string('Name_ES')->nullable()->comment('英文简称');
            $table->integer('Type')->default(1)->comment('1:联赛，2：杯赛');
            $table->integer('Mode')->default(1)->comment('比赛方式：1 分轮 2 分组');
            $table->string('SubSclass')->nullable()->comment('赛事类型名称:联赛或杯赛');
            $table->integer('Total_Round')->nullable()->comment('本赛季总轮数');
            $table->integer('Curr_Round')->nullable()->comment('当前轮次');
            $table->char('Curr_matchSeason')->nullable()->comment('当前赛季');
            $table->integer('CountryID')->comment('所属国家ID');
            $table->char('CountryName')->nullable()->comment('所属国家名称');
            $table->char('CountryEnName')->nullable()->comment('所属国家英文名称');
            $table->integer('AreaID')->comment('赛事区域ID');
            $table->string('Logo')->nullable()->comment('logo');
            $table->integer('IsStop')->nullable()->comment('是否休赛');
            $table->integer('Sclass_Sequence')->nullable()->comment('赛事排序');
            $table->integer('Bf_IsShow')->nullable()->comment('比分页面是否显示，1：显示 0：不显示');
            $table->integer('Bf_Is_Simply')->nullable()->comment('是否一级赛事，1：一级 0：普通');
            $table->integer('IsHaveSub')->nullable()->comment('1：有 0：没有');
            $table->string('BeginSeason')->nullable()->comment('起始赛季');
            $table->integer('Count_Group')->nullable()->comment('组数');
            $table->string('Sclass_Rule')->nullable()->comment('赛制');
            $table->text('Sclass_Year')->nullable()->comment('所有赛季');
            $table->text('Description')->nullable()->comment('描述');
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
        Schema::dropIfExists('zq_sclass');
    }
}