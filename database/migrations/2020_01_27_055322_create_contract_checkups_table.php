<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractCheckupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_checkups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_id');
            $table->enum('status', ['sent', 'approved', 'rejected'])->default('sent');
            $table->enum('type', ['check-up', 'teeth-cleaning']);
            $table->timestamp('request_date_at');
            $table->timestamps();
        });

        Schema::table('contract_checkups', function (Blueprint $table) {
            $table->foreign('contract_id')->references('id')->on('temporally_contracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_checkups');
    }
}
