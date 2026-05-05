<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS applications (
                application_id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                resident_id              INT UNSIGNED    NOT NULL,
                premises_id              INT UNSIGNED    NOT NULL,
                intended_business_type   VARCHAR(100)    NOT NULL,
                financial_position       DECIMAL(8,2)    NOT NULL,
                application_status       ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
                application_date         DATETIME        NOT NULL DEFAULT NOW(),
                reviewed_by              INT UNSIGNED    DEFAULT NULL,
                reviewed_at              DATETIME        DEFAULT NULL,
                remarks                  VARCHAR(255)    DEFAULT NULL,
                created_at               DATETIME        NOT NULL DEFAULT NOW(),
                updated_at               DATETIME        DEFAULT NULL ON UPDATE NOW(),
                deleted_at               DATETIME        DEFAULT NULL,
                CONSTRAINT pk_applications             PRIMARY KEY (application_id),
                CONSTRAINT fk_applications_resident    FOREIGN KEY (resident_id)
                    REFERENCES residents(resident_id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT,
                CONSTRAINT fk_applications_premises    FOREIGN KEY (premises_id)
                    REFERENCES premises(premises_id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT,
                CONSTRAINT fk_applications_reviewer    FOREIGN KEY (reviewed_by)
                    REFERENCES admins(admin_id)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::unprepared('CREATE INDEX idx_applications_status   ON applications(application_status);');
        DB::unprepared('CREATE INDEX idx_applications_resident ON applications(resident_id);');
        DB::unprepared('CREATE INDEX idx_applications_deleted  ON applications(deleted_at);');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS applications;');
    }
};
