<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Notification;
use App\Models\Premises;
use App\Models\Resident;

class NotificationService
{
    // ── Personal: application approved ───────────────────────────

    public static function applicationApproved(Application $application): void
    {
        Notification::create([
            'resident_id' => $application->resident_id,
            'type' => 'application_approved',
            'title' => 'Your Application Has Been Approved',
            'message' => 'Your application (#' . $application->application_id . ') for ' . ($application->premises->premises_name ?? 'the requested premises') . ' has been approved. Please proceed to payment to confirm your rental.',
            'related_id' => $application->application_id,
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // ── Personal: application rejected ───────────────────────────

    public static function applicationRejected(Application $application, ?string $remarks = null): void
    {
        $message = 'Your application (#' . $application->application_id . ') for ' . ($application->premises->premises_name ?? 'the requested premises') . ' has been reviewed and was not approved.';

        if ($remarks) {
            $message .= ' Remarks: ' . $remarks;
        }

        Notification::create([
            'resident_id' => $application->resident_id,
            'type' => 'application_rejected',
            'title' => 'Application Outcome: Not Approved',
            'message' => $message,
            'related_id' => $application->application_id,
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // ── Broadcast: vacancy announcement ──────────────────────────

    public static function vacancyAnnouncement(Premises $premises, string $customTitle = '', string $customMessage = ''): void
    {
        $title = $customTitle ?: 'Premises Now Available: ' . $premises->premises_name;
        $message = $customMessage ?: $premises->premises_name . ' (' . $premises->type_label . ') ' . 'located at ' . ($premises->location->location_name ?? 'Cameron Highlands') . ' is now available for rental at RM ' . number_format($premises->rental_fee, 2) . '/month. ' . 'Log in to browse and apply.';

        // resident_id = NULL → broadcast to all residents
        Notification::create([
            'resident_id' => null,
            'type' => 'vacancy_announcement',
            'title' => $title,
            'message' => $message,
            'related_id' => $premises->premises_id,
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // ── Broadcast: custom admin announcement ──────────────────────

    public static function customAnnouncement(string $title, string $message): void
    {
        Notification::create([
            'resident_id' => null,
            'type' => 'vacancy_announcement',
            'title' => $title,
            'message' => $message,
            'related_id' => null,
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }
}
