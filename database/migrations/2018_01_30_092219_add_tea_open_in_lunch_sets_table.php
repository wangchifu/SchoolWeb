<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeaOpenInLunchSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lunch_setups', function (Blueprint $table) {
            $table->string('tea_open')->after('stud_gra_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lunch_setups', function (Blueprint $table) {
            $table->dropColumn('tea_open');
        });
    }
}
