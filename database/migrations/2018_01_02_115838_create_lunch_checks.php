<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_checks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_date');
            $table->unsignedInteger('main_eat')->nullable();
            $table->unsignedInteger('main_vag')->nullable();
            $table->unsignedInteger('co_vag')->nullable();
            $table->unsignedInteger('vag')->nullable();
            $table->unsignedInteger('soup')->nullable();
            $table->string('reason')->nullable();
            $table->unsignedInteger('action');
            $table->unsignedInteger('semester');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('lunch_checks');
    }
}
