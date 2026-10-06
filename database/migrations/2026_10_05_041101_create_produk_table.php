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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');

            $table->foreignId('id_kategori')
                ->constrained('kategori', 'id_kategori')
                ->restrictOnDelete();

            $table->foreignId('id_merek')
                ->constrained('merek', 'id_merek')
                ->restrictOnDelete();

            $table->string('sku');
            $table->string('nama_produk');

            $table->enum('tipe_produk', [
                'regular',
                'prebuild'
            ]);

            $table->decimal('harga', 15, 2);
            $table->integer('stok');
            $table->integer('jumlah_terjual');

            $table->enum('kondisi', [
                'baru',
                'bekas'
            ]);

            $table->integer('masa_garansi_bulan');
            $table->text('deskripsi');
            $table->string('gambar_url');
            $table->decimal('rating_rata_rata', 3, 2);
            $table->boolean('is_active');

            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
