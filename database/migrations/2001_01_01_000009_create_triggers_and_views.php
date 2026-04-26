<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // TRIGGER 1: trg_after_agreement_created
        // After rental_agreement insert → mark premises as 'occupied'
        // ============================================================
        DB::unprepared("
            CREATE TRIGGER trg_after_agreement_created
            AFTER INSERT ON rental_agreements
            FOR EACH ROW
            BEGIN
                UPDATE premises p
                JOIN applications a ON a.application_id = NEW.application_id
                SET p.premises_status = 'occupied'
                WHERE p.premises_id = a.premises_id;
            END
        ");

        // ============================================================
        // TRIGGER 2: trg_after_agreement_status_update
        // After agreement becomes terminated/expired → mark premises 'available'
        // ============================================================
        DB::unprepared("
            CREATE TRIGGER trg_after_agreement_status_update
            AFTER UPDATE ON rental_agreements
            FOR EACH ROW
            BEGIN
                IF NEW.agreement_status IN ('terminated', 'expired')
                   AND OLD.agreement_status = 'active' THEN
                    UPDATE premises p
                    JOIN applications a ON a.application_id = NEW.application_id
                    SET p.premises_status = 'available'
                    WHERE p.premises_id = a.premises_id;
                END IF;
            END
        ");

        // ============================================================
        // TRIGGER 3: trg_after_payment_completed
        // After payment status → 'completed', auto-insert rental_agreement
        // ============================================================
        DB::unprepared("
            CREATE TRIGGER trg_after_payment_completed
            AFTER UPDATE ON payments
            FOR EACH ROW
            BEGIN
                IF NEW.payment_status = 'completed'
                   AND OLD.payment_status != 'completed' THEN
                    INSERT INTO rental_agreements (
                        application_id,
                        payment_id,
                        agreement_start_date,
                        agreement_end_date,
                        agreement_status,
                        signed_at,
                        created_at
                    )
                    VALUES (
                        NEW.application_id,
                        NEW.payment_id,
                        DATE(NEW.payment_date),
                        DATE(DATE_ADD(NEW.payment_date, INTERVAL 1 YEAR)),
                        'active',
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ");

        // ============================================================
        // VIEW 1: vw_application_details
        // ============================================================
        DB::unprepared("
            CREATE VIEW vw_application_details AS
            SELECT
                a.application_id,
                a.application_status,
                a.application_date,
                a.intended_business_type,
                a.financial_position,
                a.reviewed_at,
                a.remarks,
                r.resident_id,
                CONCAT(r.resident_first_name, ' ',
                       COALESCE(CONCAT(r.resident_middle_name, ' '), ''),
                       r.resident_last_name) AS resident_full_name,
                r.resident_ic_number,
                r.resident_phone,
                r.resident_email,
                r.residency_duration,
                r.marital_status,
                r.mdch_license_holder,
                r.business_experience,
                r.business_type,
                p.premises_id,
                p.premises_name,
                p.premises_type,
                p.rental_fee,
                p.premises_status,
                l.location_name,
                CONCAT(ad.admin_first_name, ' ',
                       COALESCE(CONCAT(ad.admin_middle_name, ' '), ''),
                       ad.admin_last_name) AS reviewed_by_name
            FROM applications a
            JOIN residents r   ON a.resident_id = r.resident_id
            JOIN premises p    ON a.premises_id = p.premises_id
            JOIN locations l   ON p.location_id = l.location_id
            LEFT JOIN admins ad ON a.reviewed_by = ad.admin_id
        ");

        // ============================================================
        // VIEW 2: vw_active_agreements
        // ============================================================
        DB::unprepared("
            CREATE VIEW vw_active_agreements AS
            SELECT
                ra.agreement_id,
                ra.agreement_status,
                ra.agreement_start_date,
                ra.agreement_end_date,
                ra.signed_at,
                r.resident_id,
                CONCAT(r.resident_first_name, ' ',
                       COALESCE(CONCAT(r.resident_middle_name, ' '), ''),
                       r.resident_last_name) AS resident_full_name,
                r.resident_ic_number,
                r.resident_phone,
                r.resident_email,
                p.premises_id,
                p.premises_name,
                p.premises_type,
                p.rental_fee,
                l.location_name,
                py.payment_id,
                py.amount,
                py.payment_date,
                py.payment_status,
                a.application_id,
                a.intended_business_type
            FROM rental_agreements ra
            JOIN applications a  ON ra.application_id = a.application_id
            JOIN residents r     ON a.resident_id = r.resident_id
            JOIN premises p      ON a.premises_id = p.premises_id
            JOIN locations l     ON p.location_id = l.location_id
            JOIN payments py     ON ra.payment_id = py.payment_id
            WHERE ra.agreement_status = 'active'
        ");

        // ============================================================
        // VIEW 3: vw_revenue_summary
        // ============================================================
        DB::unprepared("
            CREATE VIEW vw_revenue_summary AS
            SELECT
                l.location_name,
                p.premises_type,
                COUNT(py.payment_id)   AS total_payments,
                SUM(py.amount)         AS total_revenue,
                AVG(py.amount)         AS average_payment
            FROM payments py
            JOIN applications a ON py.application_id = a.application_id
            JOIN premises p     ON a.premises_id = p.premises_id
            JOIN locations l    ON p.location_id = l.location_id
            WHERE py.payment_status = 'completed'
            GROUP BY l.location_name, p.premises_type
            ORDER BY total_revenue DESC
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_agreement_created;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_agreement_status_update;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_payment_completed;');
        DB::unprepared('DROP VIEW IF EXISTS vw_application_details;');
        DB::unprepared('DROP VIEW IF EXISTS vw_active_agreements;');
        DB::unprepared('DROP VIEW IF EXISTS vw_revenue_summary;');
    }
};