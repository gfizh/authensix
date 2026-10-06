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
        Schema::create('prebuild_komponen', function (Blueprint $table) {
            $table->id('id_prebuild_komponen');

            $table->foreignId('id_produk_parent')
                ->constrained('produk', 'id_produk')
                ->cascadeOnDelete();

            $table->foreignId('id_produk_child')
                ->constrained('produk', 'id_produk')
                ->cascadeOnDelete();

            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prebuild_komponen');
    }
};
