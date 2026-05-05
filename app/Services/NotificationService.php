<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Notification;
use App\Models\Premises;
use App\Models\Resident;

class NotificationService
{
    // Personal: application approved

    public static function applicationApproved(Application $application): void
    {
        Notification::create([
            'resident_id' => $application->resident_id,
            'type' => 'application_approved',
            'title' => 'Your Application Has Been Approved',
            'message' => 'Your application (#' . $application->application_id . ') for ' . ($application->premises->premises_name ?? 'the requested premises') . ' has been approved. Please proceed to payment to confirm your rental.',
            'related_id' => $application->application_id,
            'related_type' => 'application',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // Personal: application rejected

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
            'related_type' => 'application',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // Personal: application auto-cancelled (due to another approval)
    public static function applicationAutoCancelled(Application $application, string $approvedPremisesName): void
    {
        Notification::create([
            'resident_id' => $application->resident_id,
            'type' => 'application_cancelled',
            'title' => 'Your Application Has Been Automatically Cancelled',
            'message' => 'Your application (#' . $application->application_id . ') for "' . ($application->premises->premises_name ?? 'the requested premises') . '" has been automatically cancelled because your application for "' . $approvedPremisesName . '" was approved. MDCH policy allows only one active business license at a time.',
            'related_id' => $application->application_id,
            'related_type' => 'application',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // Broadcast: vacancy announcement

    public static function vacancyAnnouncement(Premises $premises, string $customTitle = '', string $customMessage = ''): void
    {
        $title = $customTitle ?: 'Premises Now Available: ' . $premises->premises_name;
        $message = $customMessage ?: $premises->premises_name . ' (' . $premises->type_label . ') ' . 'located at ' . ($premises->location->location_name ?? 'Cameron Highlands') . ' is now available for rental at RM ' . number_format($premises->rental_fee, 2) . '/month. ' . 'Quota: ' . $premises->quota_label . '. ';

        // resident_id = NULL → broadcast to all residents
        Notification::create([
            'resident_id' => null,
            'type' => 'vacancy_announcement',
            'title' => $title,
            'message' => $message,
            'related_id' => $premises->premises_id,
            'related_type' => 'premises',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // Broadcast: vacancy update announcement (when available premises details change)
    public static function vacancyUpdated(Premises $premises, array $changes): void
    {
        $changeList = implode(', ', $changes);

        $title = 'Premises Details Updated: ' . $premises->premises_name;
        $message = 'The following details have been updated for "' . $premises->premises_name . '": ' . $changeList . '. ' . 'Please review the updated information before applying.';

        Notification::create([
            'resident_id' => null, // broadcast to all residents
            'type' => 'vacancy_updated',
            'title' => $title,
            'message' => $message,
            'related_id' => $premises->premises_id,
            'related_type' => 'premises',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // Broadcast: custom admin announcement

    public static function customAnnouncement(string $title, string $message): void
    {
        Notification::create([
            'resident_id' => null,
            'type' => 'vacancy_announcement',
            'title' => $title,
            'message' => $message,
            'related_id' => null,
            'related_type' => null,
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // Personal: premises details updated (notifies tenant)

    public static function premisesUpdated(int $residentId, Premises $premises, array $changes): void
    {
        $changeList = implode('; ', $changes);

        Notification::create([
            'resident_id' => $residentId,
            'type' => 'premises_updated',
            'title' => 'Your Premises Details Have Been Updated',
            'message' => 'MDCH has updated details for your rented premises "' . $premises->premises_name . '". ' . 'The following changes were made: ' . $changeList . '. ' . 'Please review your agreement details.',
            'related_id' => $premises->premises_id,
            'related_type' => 'premises',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }

    // Personal: agreement terminated (notifies tenant)

    public static function agreementTerminated(int $residentId, string $premisesName, int $agreementId, ?string $remarks = null): void
    {
        $message = "Your rental agreement (#{$agreementId}) for \"{$premisesName}\" has been terminated by MDCH. " . 'The premises is now available for new applications. ';

        if ($remarks) {
            $message .= " Remarks: {$remarks}";
        }

        Notification::create([
            'resident_id' => $residentId,
            'type' => 'agreement_terminated',
            'title' => 'Your Rental Agreement Has Been Terminated',
            'message' => $message,
            'related_id' => $agreementId,
            'related_type' => 'agreement',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }
}
