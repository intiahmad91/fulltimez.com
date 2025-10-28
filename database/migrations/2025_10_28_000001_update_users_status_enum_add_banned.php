<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `status` ENUM('active','inactive','pending','banned') NOT NULL DEFAULT 'active'");
        } else if ($driver === 'sqlite') {
            // No-op for SQLite (enum emulated as text). If needed, ensure values are validated at application layer.
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `status` ENUM('active','inactive','pending') NOT NULL DEFAULT 'active'");
        } else if ($driver === 'sqlite') {
            // No-op for SQLite
        }
    }
};


