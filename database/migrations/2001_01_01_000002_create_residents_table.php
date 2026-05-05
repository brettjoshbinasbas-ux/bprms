<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS residents (
                resident_id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                resident_first_name      VARCHAR(50)     NOT NULL,
                resident_middle_name     VARCHAR(50)     DEFAULT NULL,
                resident_last_name       VARCHAR(50)     NOT NULL,
                resident_ic_number       CHAR(12)        NOT NULL COMMENT '12 digits without hyphen',
                resident_phone           CHAR(12)        NOT NULL,
                resident_address         VARCHAR(255)    NOT NULL,
                resident_email           VARCHAR(100)    NOT NULL,
                resident_password        VARCHAR(255)    NOT NULL,
                residency_duration       TINYINT UNSIGNED NOT NULL COMMENT 'Years residing in Cameron Highlands',
                marital_status           ENUM('single','married','widowed','divorced') NOT NULL,
                mdch_license_holder      TINYINT(1)      NOT NULL DEFAULT 0,
                business_experience      TINYINT(1)      NOT NULL DEFAULT 0,
                business_type            VARCHAR(100)    DEFAULT NULL,
                created_at               DATETIME        NOT NULL DEFAULT NOW(),
                updated_at               DATETIME        DEFAULT NULL ON UPDATE NOW(),
                deleted_at               DATETIME        DEFAULT NULL,
                CONSTRAINT pk_residents               PRIMARY KEY (resident_id),
                CONSTRAINT uq_residents_ic            UNIQUE (resident_ic_number),
                CONSTRAINT uq_residents_email         UNIQUE (resident_email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::unprepared('CREATE INDEX idx_residents_deleted ON residents(deleted_at);');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS residents;');
    }
};
