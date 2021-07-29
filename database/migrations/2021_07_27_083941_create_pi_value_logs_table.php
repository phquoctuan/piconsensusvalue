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
            $table->timestamp('propose_time');
            $table->integer('propose_id')->default(0);
            $table->timestamp('created_at')->useCurrent = true;
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
