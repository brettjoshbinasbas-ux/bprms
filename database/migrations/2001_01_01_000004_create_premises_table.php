<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS premises (
                premises_id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                location_id          INT UNSIGNED    NOT NULL,
                premises_name        VARCHAR(100)    NOT NULL,
                premises_type        ENUM('business_premises','market_table','market_stall','food_stall','handicraft','workshop','various') NOT NULL,
                premises_description VARCHAR(255)    DEFAULT NULL,
                applicant_quota      VARCHAR(20)     NOT NULL DEFAULT 'open',
                rental_fee           DECIMAL(8,2)    NOT NULL,
                premises_status      ENUM('available','occupied','unavailable') NOT NULL DEFAULT 'available',
                created_at           DATETIME        NOT NULL DEFAULT NOW(),
                updated_at           DATETIME        DEFAULT NULL ON UPDATE NOW(),
                CONSTRAINT pk_premises               PRIMARY KEY (premises_id),
                CONSTRAINT fk_premises_location      FOREIGN KEY (location_id)
                    REFERENCES locations(location_id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::unprepared("
            CREATE INDEX idx_premises_status   ON premises(premises_status);
        ");
        DB::unprepared("
            CREATE INDEX idx_premises_location ON premises(location_id);
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS premises;');
    }
};
