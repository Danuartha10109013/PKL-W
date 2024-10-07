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
        Schema::create('incentive', function (Blueprint $table) {
            $table->id();
            $table->string('no');
            $table->string('jobcard_id');
            $table->string('form');
            $table->string('jo_number');
            $table->string('jo_date');
            $table->string('kurs')->nullable();
            $table->integer('gp');
            $table->integer('total_it');
            $table->integer('total_it');
            $table->integer('sales_enginer');
            $table->integer('aplication_service');
            $table->integer('administration');
            $table->integer('manager');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incentive');
    }
};
