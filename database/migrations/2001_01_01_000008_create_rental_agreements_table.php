<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS rental_agreements (
                agreement_id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                application_id         INT UNSIGNED    NOT NULL,
                payment_id             INT UNSIGNED    NOT NULL,
                agreement_start_date   DATE            NOT NULL,
                agreement_end_date     DATE            NOT NULL,
                agreement_status       ENUM('active','expired','terminated') NOT NULL DEFAULT 'active',
                signed_at              DATETIME        NOT NULL DEFAULT NOW(),
                created_at             DATETIME        NOT NULL DEFAULT NOW(),
                updated_at             DATETIME        DEFAULT NULL ON UPDATE NOW(),
                CONSTRAINT pk_rental_agreements          PRIMARY KEY (agreement_id),
                CONSTRAINT uq_rental_agreements_app      UNIQUE (application_id),
                CONSTRAINT uq_rental_agreements_payment  UNIQUE (payment_id),
                CONSTRAINT fk_rental_agreements_app      FOREIGN KEY (application_id)
                    REFERENCES applications(application_id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT,
                CONSTRAINT fk_rental_agreements_payment  FOREIGN KEY (payment_id)
                    REFERENCES payments(payment_id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::unprepared("CREATE INDEX idx_agreements_status ON rental_agreements(agreement_status);");
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS rental_agreements;');
    }
};