<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatictisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statictis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label', 255);
            $table->integer('from');
            $table->integer('to')->nullable();
            $table->integer('total')->default(0);
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
        Schema::table('statictis', function (Blueprint $table) {
            Schema::dropIfExists('statictis');
        });
    }
}
