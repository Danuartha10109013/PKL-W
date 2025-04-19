<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jobcard', function (Blueprint $table) {
            $table->id(); // id bigint unsigned
            $table->string('no_jobcard');
            $table->date('date');
            $table->integer('kurs');
            $table->string('customer_name');
            $table->string('no_po');
            $table->date('po_date');
            $table->date('po_received');
            $table->integer('totalbop');
            $table->integer('totalsp');
            $table->integer('totalbp');
            $table->string('no_form');
            $table->date('effective_date');
            $table->integer('no_revisi');
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobcard');
    }
};
