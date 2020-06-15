<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemporallyContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporally_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dentist_id');
            $table->string('dentist_address', 42);
            $table->string('dentist_street_address');
            $table->string('dentist_country');
            $table->string('dentist_name');
            $table->string('dentist_email', 100);
            $table->string('dentist_phone', 100);
            $table->string('dentist_website', 500);
            $table->integer('patient_id')->nullable();
            $table->string('patient_fname', 100);
            $table->string('patient_lname', 100);
            $table->string('patient_full_name')->nullable();
            $table->string('patient_email', 100);
            $table->string('patient_id_number', 100)->nullable();
            $table->string('patient_address', 42)->nullable();
            $table->string('patient_street_address')->nullable();
            $table->string('patient_country')->nullable();
            $table->string('professional_company_number', 100)->nullable();
            $table->string('general_dentistry');
            $table->float('monthly_premium');
            $table->tinyInteger('check_ups_per_year');
            $table->tinyInteger('teeth_cleaning_per_year');
            $table->timestamp('contract_active_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->string('document_hash')->nullable();
            $table->string('slug')->unique();
            $table->enum('status', ['pending', 'awaiting-payment', 'awaiting-approval', 'active', 'cancelled'])->default('pending');
            $table->tinyInteger('is_processing')->default(0);
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
        Schema::dropIfExists('temporally_contracts');
    }
}
