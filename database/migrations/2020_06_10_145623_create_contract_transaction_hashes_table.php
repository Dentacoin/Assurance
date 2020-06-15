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
            $table->unsignedInteger('contract_slug')->unique();
            $table->string('transactionHash');
            $table->enum('to_status', ['awaiting-approval', 'active', 'active-withdraw', 'cancelled']);
            $table->text('data')->nullable();
            $table->tinyInteger('synced_with_assurance_db')->default(0);
            $table->timestamps();
        });

        Schema::table('contract_transaction_hashes', function (Blueprint $table) {
            $table->foreign('slug')->references('id')->on('temporally_contracts')->onDelete('cascade');
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
