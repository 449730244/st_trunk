<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZqSubClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zq_subsclass', function (Blueprint $table) {
            $table->increments('SubSclassID');
            $table->unsignedInteger('SclassID')->comment('联赛ID(对应zq_sclass表)');
            $table->boolean('IsHaveScore')->nullable()->comment('是否有积分 1有，0无。在统计积分中，只统计有积分的子联赛');
            $table->unsignedInteger('sortNumber')->nullable()->comment('排序');
            $table->unsignedInteger('Curr_round')->nullable()->comment('当前轮数');
            $table->unsignedInteger('Count_round')->nullable()->comment('总轮数');
            $table->boolean('IsCurrentSclass')->nullable()->comment('是否当前子联赛');
            $table->string('subSclassName')->comment('子联赛名');
            $table->string('subName_JS')->nullable()->comment('子联赛缩略名');
            $table->string('SubName_Es')->nullable()->comment('子联赛英缩略名');
            $table->string('subName_Fs')->nullable()->comment('子联赛繁缩略名');
            $table->text('includeSeason')->nullable()->comment('包含的子赛季');
            $table->boolean('IsZu')->nullable()->comment('是否分局');
            $table->unsignedInteger('groupNum')->nullable()->comment('分组数目，如：6表示A-F组');
            $table->string('MatchSeason')->nullable()->comment('赛季');
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
        Schema::dropIfExists('zq_subsclass');
    }
}
