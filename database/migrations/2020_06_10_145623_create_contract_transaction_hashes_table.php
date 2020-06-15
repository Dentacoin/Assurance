<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractTransactionHashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_transaction_hashes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_id');
            $table->string('transactionHash');
            $table->enum('status', ['awaiting-payment', 'awaiting-approval', 'withdraw', 'cancelled']);
            $table->tinyInteger('synced_with_assurance_db')->default(0);
            $table->timestamps();
        });

        Schema::table('contract_transaction_hashes', function (Blueprint $table) {
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
        Schema::dropIfExists('contract_transaction_hashes');
    }
}
