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
    Schema::table('kategori', function (Blueprint $table) {
        $table->unique('slug');
    });

    Schema::table('merek', function (Blueprint $table) {
        $table->unique('nama_merek');
    });

    Schema::table('pengiriman', function (Blueprint $table) {
        $table->unique('id_pesanan');
    });
}

public function down(): void
{
    Schema::table('kategori', function (Blueprint $table) {
        $table->dropUnique('kategori_slug_unique');
    });

    Schema::table('merek', function (Blueprint $table) {
        $table->dropUnique('merek_nama_merek_unique');
    });

    Schema::table('pengiriman', function (Blueprint $table) {
        $table->dropUnique(['id_pesanan']);
    });
}
};
