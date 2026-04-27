<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS notifications (
                notification_id   INT UNSIGNED     NOT NULL AUTO_INCREMENT,
                resident_id       INT UNSIGNED     DEFAULT NULL COMMENT 'NULL = broadcast to all residents',
                type              ENUM(
                                    'vacancy_announcement',
                                    'application_approved',
                                    'application_rejected',
                                    'application_cancelled'
                                  ) NOT NULL,
                title             VARCHAR(150)     NOT NULL,
                message           TEXT             NOT NULL,
                is_read           TINYINT(1)       NOT NULL DEFAULT 0,
                related_id        INT UNSIGNED     DEFAULT NULL COMMENT 'application_id or premises_id depending on type',
                created_at        DATETIME         NOT NULL DEFAULT NOW(),
                CONSTRAINT pk_notifications          PRIMARY KEY (notification_id),
                CONSTRAINT fk_notifications_resident FOREIGN KEY (resident_id)
                    REFERENCES residents(resident_id)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::unprepared("CREATE INDEX idx_notifications_resident ON notifications(resident_id);");
        DB::unprepared("CREATE INDEX idx_notifications_is_read  ON notifications(is_read);");
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS notifications;');
    }
};