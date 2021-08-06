<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donate_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('from_date');
            $table->timestamp('to_date');
            $table->integer('id_from')->nullable();
            $table->integer('id_to')->nullable();
            $table->double('all_donate')->default(0);//from initial
            $table->double('total_donate')->default(0);//this period
            $table->timestamp('draw_date')->nullable();//first day of next month
            $table->integer('drawed_id')->nullable();//proposal id
            $table->string('drawed_username', 256)->nullable();//winner
            $table->boolean('paid')->nullable();//mark transferred for winner this month
            $table->string('txid')->nullable();
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
        Schema::dropIfExists('donate_logs');
    }
}
