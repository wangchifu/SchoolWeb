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
            $table->unsignedInteger('semester')->unique();
            $table->unsignedInteger('tea_money');
            $table->unsignedInteger('stud_money');
            $table->unsignedInteger('stud_back_money');
            $table->unsignedInteger('support_part_money');
            $table->unsignedInteger('support_all_money');
            $table->unsignedInteger('die_line');
            $table->string('place');
            $table->string('factory');
            $table->string('stud_gra_date')->nullable();
            $table->string('disable')->nullable();
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
