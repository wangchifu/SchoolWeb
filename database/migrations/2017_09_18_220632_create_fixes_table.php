<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fun_id');
            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->text('content');
            $table->string('reply')->nullable();
            $table->unsignedInteger('done')->nullable();
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
        Schema::dropIfExists('fixes');
    }
}
