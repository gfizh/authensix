<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE users
                MODIFY role ENUM('customer', 'admin', 'superadmin')
                NOT NULL
            ");
        }
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

        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE users
                MODIFY role ENUM('customer', 'admin')
                NOT NULL
            ");
        }
    }
};
