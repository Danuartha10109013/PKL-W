<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomisiMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komisi_m', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable();
            $table->string('no_jobcard')->nullable();
            $table->string('customer_name')->nullable();
            $table->date('date')->nullable();
            $table->string('no_po')->nullable();
            $table->decimal('kurs', 10, 2)->nullable();
            $table->decimal('gp', 15, 2)->nullable();
            $table->decimal('it', 15, 2)->nullable();
            $table->decimal('se', 15, 2)->nullable();
            $table->decimal('as', 15, 2)->nullable();
            $table->decimal('adm', 15, 2)->nullable();
            $table->decimal('mng', 15, 2)->nullable();
            $table->string('no_jo')->nullable();
            $table->date('jo_date')->nullable();
            $table->string('sales_name')->nullable();
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
        Schema::dropIfExists('komisi_m');
    }
}
