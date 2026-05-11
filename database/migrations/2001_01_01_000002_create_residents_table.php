<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS residents (
                resident_id              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
                resident_first_name      VARCHAR(50)      NOT NULL,
                resident_middle_name     VARCHAR(50)      DEFAULT NULL,
                resident_last_name       VARCHAR(50)      NOT NULL,
                resident_ic_number       CHAR(12)         NOT NULL
                    COMMENT '12 digits without hyphen',
                resident_phone           CHAR(12)         NOT NULL,
                resident_address_line1   VARCHAR(100)     NOT NULL
                    COMMENT 'Unit/house number and street name',
                resident_address_line2   VARCHAR(100)     DEFAULT NULL
                    COMMENT 'Area, taman, or estate name (optional)',
                resident_postcode        CHAR(5)          NOT NULL
                    COMMENT 'Malaysian 5-digit postcode',
                resident_city            VARCHAR(50)      NOT NULL
                    COMMENT 'City or town',
                resident_state           VARCHAR(50)      NOT NULL
                    COMMENT 'Malaysian state',
                resident_email           VARCHAR(100)     NOT NULL,
                resident_password        VARCHAR(255)     NOT NULL,
                residency_duration       TINYINT UNSIGNED NOT NULL
                    COMMENT 'Years residing in Cameron Highlands',
                marital_status           ENUM('single','married','widowed','divorced') NOT NULL,
                mdch_license_holder      TINYINT(1)       NOT NULL DEFAULT 0,
                business_experience      TINYINT(1)       NOT NULL DEFAULT 0,
                business_type            VARCHAR(100)     DEFAULT NULL,
                created_at               DATETIME         NOT NULL DEFAULT NOW(),
                updated_at               DATETIME         DEFAULT NULL ON UPDATE NOW(),
                deleted_at               DATETIME         DEFAULT NULL,
                CONSTRAINT pk_residents      PRIMARY KEY (resident_id),
                CONSTRAINT uq_residents_ic   UNIQUE (resident_ic_number),
                CONSTRAINT uq_residents_email UNIQUE (resident_email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::unprepared('CREATE INDEX idx_residents_deleted ON residents(deleted_at);');
        DB::unprepared('CREATE INDEX idx_residents_city    ON residents(resident_city);');
        DB::unprepared('CREATE INDEX idx_residents_state   ON residents(resident_state);');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS residents;');
    }
};