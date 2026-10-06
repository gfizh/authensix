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
        Schema::create('pembayaran', function (Blueprint $table) {
             $table->id('id_pembayaran');

            $table->foreignId('id_pesanan')
                ->constrained('pesanan', 'id_pesanan')
                ->cascadeOnDelete();

            $table->string('metode_pembayaran');

            $table->enum('status_pembayaran', [
                'pending',
                'dibayar',
                'gagal',
                'expired'
            ]);

            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('kode_transaksi_gateway');

            $table->dateTime('tanggal_pembayaran');
            $table->dateTime('batas_waktu_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
