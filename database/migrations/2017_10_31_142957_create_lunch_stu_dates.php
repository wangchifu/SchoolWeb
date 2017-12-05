<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchStuDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_stu_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_date');
            $table->string('enable');
            $table->unsignedInteger('semester');
            $table->unsignedInteger('lunch_order_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('p_id');
            $table->unsignedInteger('eat_style');
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
        Schema::dropIfExists('lunch_stu_dates');
    }
}
