<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_logs', function (Blueprint $table) {
            $table->id('id_action_log');
            $table->unsignedBigInteger('id_user')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('aktivitas');
            $table->text('detail')->nullable();
            $table->string('modul')->default('Sistem');
            $table->string('ip_address', 45)->nullable();
            $table->string('status', 20)->default('Berhasil');
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_logs');
    }
};
