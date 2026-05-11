<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS notifications (
                notification_id       INT UNSIGNED  NOT NULL AUTO_INCREMENT,
                resident_id           INT UNSIGNED  DEFAULT NULL
                    COMMENT 'NULL = broadcast to all residents',
                type                  ENUM(
                                        'vacancy_announcement',
                                        'vacancy_updated',
                                        'application_approved',
                                        'application_rejected',
                                        'application_cancelled',
                                        'premises_updated',
                                        'agreement_terminated'
                                      ) NOT NULL,
                title                 VARCHAR(150)  NOT NULL,
                message               TEXT          NOT NULL,
                is_read               TINYINT(1)    NOT NULL DEFAULT 0,
                related_application_id INT UNSIGNED DEFAULT NULL
                    COMMENT 'FK to applications — personal application notifications',
                related_premises_id   INT UNSIGNED  DEFAULT NULL
                    COMMENT 'FK to premises — vacancy and premises notifications',
                created_at            DATETIME      NOT NULL DEFAULT NOW(),
                CONSTRAINT pk_notifications PRIMARY KEY (notification_id),
                CONSTRAINT fk_notifications_resident
                    FOREIGN KEY (resident_id)
                    REFERENCES residents(resident_id)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT fk_notifications_application
                    FOREIGN KEY (related_application_id)
                    REFERENCES applications(application_id)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL,
                CONSTRAINT fk_notifications_premises
                    FOREIGN KEY (related_premises_id)
                    REFERENCES premises(premises_id)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::unprepared('CREATE INDEX idx_notifications_resident    ON notifications(resident_id);');
        DB::unprepared('CREATE INDEX idx_notifications_is_read     ON notifications(is_read);');
        DB::unprepared('CREATE INDEX idx_notifications_application ON notifications(related_application_id);');
        DB::unprepared('CREATE INDEX idx_notifications_premises    ON notifications(related_premises_id);');
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS notifications;');
    }
};