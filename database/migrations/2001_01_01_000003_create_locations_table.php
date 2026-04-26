<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS locations (
                location_id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                location_name        VARCHAR(50)     NOT NULL,
                location_description VARCHAR(200)    DEFAULT NULL,
                created_at           DATETIME        NOT NULL DEFAULT NOW(),
                CONSTRAINT pk_locations              PRIMARY KEY (location_id),
                CONSTRAINT uq_locations_name         UNIQUE (location_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS locations;');
    }
};