<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('komisi', function (Blueprint $table) {
            $table->id();
            $table->string('no_jobcard');
            $table->string('customer_name');
            $table->date('date');
            $table->string('no_po');
            $table->decimal('kurs', 20, 2);
            $table->decimal('totalbop', 20, 2);
            $table->decimal('totalsp', 20, 2);
            $table->decimal('totalbp', 20, 2);
            $table->date('po_date');
            $table->date('po_received');
            $table->string('no_form');
            $table->date('effective_date');
            $table->string('no_revisi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komisi');
    }
};
