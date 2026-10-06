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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');

            $table->string('nomor_pesanan');

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->restrictOnDelete();

            $table->foreignId('id_alamat')
                ->constrained('alamat_pengiriman', 'id_alamat')
                ->restrictOnDelete();

            $table->decimal('subtotal', 15, 2);
            $table->decimal('biaya_pengiriman', 15, 2);
            $table->decimal('pajak', 15, 2);
            $table->decimal('total_pembayaran', 15, 2);

            $table->enum('status_pemesanan', [
                'pending',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ]);

            $table->dateTime('tanggal_pesanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
