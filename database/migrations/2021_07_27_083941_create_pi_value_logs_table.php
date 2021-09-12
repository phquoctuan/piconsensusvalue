<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiValueLogsTable extends Migration
{
    public $timestamps = false;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_value_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->double('current_value');
            $table->integer('total_propose');
            $table->double('sum_donate');
            $table->dateTime('propose_time');
            $table->integer('propose_id')->default(0);
            $table->dateTime('created_at')->useCurrent = true;
            // ath_value
            // atl_value
            // ath_propose
            // atl_propose
            // ath_donate
            // atl_donate
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pi_value_logs');
    }
}
