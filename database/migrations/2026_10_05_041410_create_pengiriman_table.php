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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');

            $table->foreignId('id_pesanan')
                ->constrained('pesanan', 'id_pesanan')
                ->cascadeOnDelete();

            $table->string('kurir');
            $table->string('layanan');
            $table->string('nomor_resi');

            $table->enum('status_pengiriman', [
                'diproses',
                'dikirim',
                'dalam_perjalanan',
                'diterima',
                'gagal'
            ]);

            $table->dateTime('tanggal_pengiriman')->nullable();
            $table->dateTime('tanggal_diterima')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
