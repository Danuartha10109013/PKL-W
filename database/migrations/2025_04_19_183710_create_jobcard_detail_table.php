<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jobcard_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobcard_id');
            $table->integer('qty');
            $table->integer('unit_bop');
            $table->integer('total_bop');
            $table->integer('unit_sp');
            $table->integer('total_sp');
            $table->integer('unit_bp');
            $table->integer('total_bp');
            $table->string('remarks')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('jobcard_id')->references('id')->on('jobcard')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobcard_detail');
    }
};
