<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZqLineupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zq_lineup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ScheduleID')->comment('比赛ID');
            $table->char('HomeLineup')->comment('主队阵容');
            $table->char('awayLineup')->comment('客队阵容');
            $table->json('HomeLineupFirst')->comment('主队首发');
            $table->json('AwayLineupFirst')->comment('客队首发');
            $table->json('HomeBackup')->comment('主队后备');
            $table->json('AwayBackup')->comment('客队后备');
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
        Schema::dropIfExists('zq_lineup');
    }
}
