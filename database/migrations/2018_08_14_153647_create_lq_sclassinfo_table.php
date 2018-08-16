<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLqSclassinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lq_sclassinfo', function (Blueprint $table) {
            $table->increments('InfoID')->unique()->comment('国家ID');
            $table->string('NameCN')->nullable()->comment('国家简体名');
            $table->string('NameFN')->nullable()->comment('国家繁体名');
            $table->string('NameEN')->nullable()->comment('国家英文名');
            $table->string('FlagPic')->nullable()->comment('国家图标');
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
        Schema::dropIfExists('lq_sclassinfo');
    }
}
