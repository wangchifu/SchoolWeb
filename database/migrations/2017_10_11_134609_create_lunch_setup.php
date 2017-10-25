                                                                                                                <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('semester')->unique();
            $table->unsignedInteger('tea_money');
            $table->unsignedInteger('stud_money');
            $table->unsignedInteger('stud_back_money');
            $table->unsignedInteger('die_line');
            $table->string('stud_gra_date')->nullable();
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
        Schema::dropIfExists('lunch_setups');
    }
}
