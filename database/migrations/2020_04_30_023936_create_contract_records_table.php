<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_id');
            $table->enum('type', ['Contract creation', 'Contract signing', 'Contract funding', 'Contract approval', 'Successful payment', 'Contract cancelled'])->default('pending');
            $table->string('data')->nullable();
            $table->timestamps();
        });

        Schema::table('contract_records', function (Blueprint $table) {
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
        Schema::dropIfExists('contract_records');
    }
}
