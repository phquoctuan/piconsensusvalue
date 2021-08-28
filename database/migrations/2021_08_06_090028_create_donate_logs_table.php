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
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->integer('id_from')->nullable();
            $table->integer('id_to')->nullable();
            $table->integer('count_donate')->default(0);
            $table->double('total_propose')->default(0);//this period
            $table->double('total_donate')->default(0);//this period
            $table->double('reward')->nullable();//reward this period
            $table->double('remain_donate')->nullable();//reward this period
            $table->dateTime('draw_date')->nullable();//first day of next month
            $table->integer('drawed_id')->nullable();//proposal id
            $table->string('drawed_username', 256)->nullable();//winner
            $table->boolean('paid')->nullable();//mark transferred for winner this month
            $table->string('txid')->nullable();
            $table->double('fee')->nullable();//reward transfer fee
            $table->string('fromwallet')->nullable();
            $table->string('towallet')->nullable();
            $table->double('reward2')->nullable();
            $table->integer('drawed_id2')->nullable();//proposal id 2
            $table->string('drawed_username2', 256)->nullable();//winner
            $table->boolean('paid2')->nullable();//mark transferred for winner this month
            $table->string('txid2')->nullable();
            $table->double('fee2')->nullable();//reward transfer fee
            $table->string('fromwallet2')->nullable();
            $table->string('towallet2')->nullable();
            $table->double('reward3')->nullable();
            $table->integer('drawed_id3')->nullable();//proposal id 2
            $table->string('drawed_username3', 256)->nullable();//winner
            $table->boolean('paid3')->nullable();//mark transferred for winner this month
            $table->string('txid3')->nullable();
            $table->double('fee3')->nullable();//reward transfer fee
            $table->string('fromwallet3')->nullable();
            $table->string('towallet3')->nullable();
            $table->dateTime('created_at')->useCurrent = true;
            $table->dateTime('update_at')->nullable();
            $table->boolean('fixed_drawdate')->nullable();//mark to fix draw datetime
            $table->string('live_drawlink', 256)->nullable();
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
