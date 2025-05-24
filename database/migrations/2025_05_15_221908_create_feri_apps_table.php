<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feriapp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('status');

            $table->string('transport_mode');
            $table->string('transporter_company');
            $table->string('entry_border_drc');
            $table->string('truck_details');
            $table->string('arrival_station');
            $table->string('final_destination');

            $table->string('importer_name');
            $table->string('importer_phone');
            $table->string('importer_email')->nullable();
            $table->string('fix_number')->nullable();

            $table->string('exporter_name');
            $table->string('exporter_phone');
            $table->string('exporter_email')->nullable();

            $table->string('cf_agent');
            $table->string('cf_agent_contact');

            $table->text('cargo_description');
            $table->string('hs_code');
            $table->string('package_type');
            $table->integer('quantity');

            $table->string('company_ref')->nullable();
            $table->string('cargo_origin')->nullable();
            $table->string('customs_decl_no')->nullable();
            $table->string('manifest_no')->nullable();
            $table->string('occ_bivac')->nullable();
            $table->text('instructions')->nullable();

            $table->string('fob_currency')->nullable();
            $table->string('fob_value')->nullable();
            $table->string('incoterm')->nullable();
            $table->string('freight_currency')->nullable();
            $table->string('freight_value')->nullable();
            $table->string('insurance_currency')->nullable();
            $table->string('insurance_value')->nullable();
            $table->string('additional_fees_currency')->nullable();
            $table->string('additional_fees_value')->nullable();
            $table->string('documents_upload')->nullable(); // store filename/path

            $table->timestamps();

            // Foreign key constraint (optional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feriapp');
    }
};