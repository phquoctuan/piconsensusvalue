<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColDonateLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donate_logs', function (Blueprint $table) {
            $table->double('ath_value')->nullable();
            $table->double('atl_value')->nullable();
            $table->double('ath_propose')->nullable();
            $table->double('atl_propose')->nullable();
            $table->double('ath_donate')->nullable();
            $table->double('atl_donate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donate_logs', function (Blueprint $table) {
            $table->dropColumn('ath_value');
            $table->dropColumn('atl_value');
            $table->dropColumn('ath_propose');
            $table->dropColumn('atl_propose');
            $table->dropColumn('ath_donate');
            $table->dropColumn('atl_donate');
        });
    }
}
