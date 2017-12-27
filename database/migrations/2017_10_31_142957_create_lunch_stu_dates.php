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
            $table->string('enable');//eat訂餐；not未供餐；abs請假;out轉出生；no_eat；沒有訂餐
            $table->unsignedInteger('semester');
            $table->unsignedInteger('lunch_order_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('class_id');
            $table->string('num');
            $table->unsignedInteger('p_id');//101一般,201~210弱勢,301轉入,401轉出
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
