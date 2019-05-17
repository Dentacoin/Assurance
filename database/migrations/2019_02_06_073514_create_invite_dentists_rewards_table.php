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
            $table->string('title')->nullable();
            $table->string('name');
            $table->string('website', 500);
            $table->string('phone', 50)->nullable();
            $table->tinyInteger('sent_to_api')->default(0);
            $table->tinyInteger('dentist_registered_and_approved')->default(0);
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
