<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalTalbe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid');
            $table->string('username', 256);
            $table->string('paymentid');
            $table->double('propose');
            $table->double('current');
            $table->double('donate')->default(0);
            $table->string('note')->nullable();
            $table->ipAddress('ipaddress', 40)->nullable();
            $table->string('txid')->nullable();
            $table->string('txlink')->nullable();
            $table->string('fromwallet')->nullable();
            $table->string('towallet')->nullable();
            $table->smallInteger('status')->default(0);//1: complete, 2: Cancel, 3: error
            $table->boolean('completed')->default(0);
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
        Schema::dropIfExists('proposal_talbe');
    }
}
