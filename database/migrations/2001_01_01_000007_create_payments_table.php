<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS payments (
                payment_id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                application_id       INT UNSIGNED    NOT NULL,
                amount               DECIMAL(8,2)    NOT NULL,
                card_number          VARCHAR(255)    NOT NULL COMMENT 'Encrypted in production',
                card_expiry_date     DATE            NOT NULL,
                payment_date         DATETIME        NOT NULL DEFAULT NOW(),
                payment_status       ENUM('pending','completed','failed') NOT NULL DEFAULT 'pending',
                created_at           DATETIME        NOT NULL DEFAULT NOW(),
                CONSTRAINT pk_payments               PRIMARY KEY (payment_id),
                CONSTRAINT uq_payments_application   UNIQUE (application_id),
                CONSTRAINT fk_payments_application   FOREIGN KEY (application_id)
                    REFERENCES applications(application_id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS payments;');
    }
};