<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('customer', 'admin', 'superadmin')
            NOT NULL
        ");
    }

    public function down(): void
    {
        $hasSuperAdmin = DB::table('users')
            ->where('role', 'superadmin')
            ->exists();

        if ($hasSuperAdmin) {
            throw new RuntimeException(
                'Tidak dapat rollback: masih terdapat akun SUPERADMIN.'
            );
        }

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('customer', 'admin')
            NOT NULL
        ");
    }
};