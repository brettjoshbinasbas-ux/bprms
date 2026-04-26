<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS admins (
                admin_id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                admin_first_name    VARCHAR(50)     NOT NULL,
                admin_middle_name   VARCHAR(50)     DEFAULT NULL,
                admin_last_name     VARCHAR(50)     NOT NULL,
                admin_email         VARCHAR(100)    NOT NULL,
                admin_password      VARCHAR(255)    NOT NULL,
                created_at          DATETIME        NOT NULL DEFAULT NOW(),
                updated_at          DATETIME        DEFAULT NULL ON UPDATE NOW(),
                CONSTRAINT pk_admins               PRIMARY KEY (admin_id),
                CONSTRAINT uq_admins_email         UNIQUE (admin_email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS admins;');
    }
};