<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchStuOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_stu_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester');
            $table->unsignedInteger('student_id');
            $table->string('student_num');
            $table->unsignedInteger('p_id');
            $table->unsignedInteger('eat_style');
            $table->string('out_in')->nullable();//轉出或轉入
            $table->string('change_date')->nullable();//轉出入的變化時間
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
        Schema::dropIfExists('lunch_stu_orders');
    }
}
