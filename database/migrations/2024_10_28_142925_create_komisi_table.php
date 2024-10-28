<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('komisi', function (Blueprint $table) {
            $table->id();
            $table->string('no_jobcard')->unique(); // Unique job card number
            $table->string('customer_name'); // Customer's name
            $table->date('date'); // Date of the commission
            $table->string('no_po'); // Purchase order number
            $table->decimal('kurs', 10, 2); // Exchange rate
            $table->decimal('totalbop', 10, 2); // Total BOP
            $table->decimal('totalsp', 10, 2); // Total SP
            $table->decimal('totalbp', 10, 2); // Total BP
            $table->date('po_date'); // PO date
            $table->date('po_received'); // PO received date
            $table->string('no_form'); // Form number
            $table->date('effective_date'); // Effective date
            $table->string('no_revisi'); // Revision number
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komisi');
    }
};
