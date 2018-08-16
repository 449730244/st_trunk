<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZqMatchSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zq_match_seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sclassId')->comment('赛事表SClassID');
            $table->string('season')->comment('赛季');
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
        Schema::dropIfExists('zq_match_seasons');
    }
}
