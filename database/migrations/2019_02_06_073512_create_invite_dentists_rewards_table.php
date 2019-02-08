<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteDentistsRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_dentists_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->string('dentist_email', 100)->unique();
            $table->string('title');
            $table->string('name');
            $table->string('website', 500);
            $table->string('phone', 50)->nullable();
            $table->timestamp('payed_on')->nullable();
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
        Schema::dropIfExists('invite_dentists_rewards');
    }
}
