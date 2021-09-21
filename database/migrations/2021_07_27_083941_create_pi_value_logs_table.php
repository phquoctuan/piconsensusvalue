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

            // $table->double('ath_value')->nullable();
            // $table->double('atl_value')->nullable();
            // $table->double('ath_propose')->nullable();
            // $table->double('atl_propose')->nullable();
            // $table->double('ath_donate')->nullable();
            // $table->double('atl_donate')->nullable();

            $table->dateTime('created_at')->useCurrent = true;
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
