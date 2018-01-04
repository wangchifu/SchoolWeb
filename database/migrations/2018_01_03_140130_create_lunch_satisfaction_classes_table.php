<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchSatisfactionClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_satisfaction_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class_people');
            $table->string('q1_1');
            $table->string('q1_2');
            $table->string('q1_3');
            $table->string('q1_4');
            $table->string('q1_5');
            $table->string('q2_1');
            $table->string('q2_2');
            $table->string('q3_1');
            $table->string('q3_2');
            $table->string('q3_3');
            $table->string('q3_4');
            $table->string('q3_5');
            $table->string('q3_6');
            $table->string('q3_7');
            $table->string('q3_8');
            $table->string('q3_9');
            $table->string('q3_10');
            $table->string('q4_1');
            $table->string('q4_2');
            $table->string('favority')->nullable();
            $table->string('suggest')->nullable();
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('lunch_satisfaction_id');
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
        Schema::dropIfExists('lunch_satisfaction_classes');
    }
}
