<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS documents (
                document_id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
                application_id       INT UNSIGNED    NOT NULL,
                document_type        ENUM('ic_copy','applicant_photo','spouse_photo','supporting_document') NOT NULL,
                document_filename    VARCHAR(100)    NOT NULL,
                document_path        VARCHAR(255)    NOT NULL,
                uploaded_at          DATETIME        NOT NULL DEFAULT NOW(),
                CONSTRAINT pk_documents              PRIMARY KEY (document_id),
                CONSTRAINT fk_documents_application  FOREIGN KEY (application_id)
                    REFERENCES applications(application_id)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS documents;');
    }
};