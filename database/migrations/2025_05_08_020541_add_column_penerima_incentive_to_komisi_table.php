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
        Schema::table('komisi_m', function (Blueprint $table) {
            $table->json('penerimase')->nullable()->after('no_it'); // letakkan setelah kolom id
            $table->json('penerimaap')->nullable()->after('penerimase'); // letakkan setelah kolom id
            $table->json('penerimaadm')->nullable()->after('penerimaap'); // letakkan setelah kolom id
            $table->json('penerimamng')->nullable()->after('penerimaadm'); // letakkan setelah kolom id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komisi_m', function (Blueprint $table) {
            $table->dropColumn('penerimase');
            $table->dropColumn('penerimaap');
            $table->dropColumn('penerimaadm');
            $table->dropColumn('penerimamng');
        });
    }
};
