<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;

class Premises extends Model
{
    protected $table = 'premises';
    protected $primaryKey = 'premises_id';
    public $timestamps = false;

    protected $fillable = ['location_id', 'premises_name', 'premises_type', 'premises_description', 'rental_fee', 'premises_status'];

    // Relationships

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'premises_id', 'premises_id');
    }

    // The one active rental agreement on this premises (if any)
    public function activeAgreement()
    {
        return $this->hasOneThrough(
            RentalAgreement::class,
            Application::class,
            'premises_id', // FK on applications
            'application_id', // FK on rental_agreements
            'premises_id', // local key on premises
            'application_id', // local key on applications
        )->where('rental_agreements.agreement_status', 'active');
    }

    // Current occupying resident (via active agreement chain)
    public function currentTenant()
    {
        return $this->hasOneThrough(
            Resident::class,
            Application::class,
            'premises_id', // FK on applications
            'resident_id', // FK on residents
            'premises_id', // local key on premises
            'resident_id', // local key on applications
        )->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'));
    }

    // Accessors 

    // Formatted type label
    public function getTypeLabelAttribute(): string
    {
        return match ($this->premises_type) {
            'business_premises' => 'Business Premises',
            'market_table' => 'Market Table',
            'market_stall' => 'Market Stall',
            'food_stall' => 'Food Stall',
            'handicraft' => 'Handicraft',
            'workshop' => 'Workshop',
            'various' => 'Various',
            default => ucfirst($this->premises_type),
        };
    }

    // Status badge CSS class
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->premises_status) {
            'available' => 'badge-available',
            'occupied' => 'badge-occupied',
            'unavailable' => 'badge-unavailable',
            default => 'bg-secondary',
        };
    }

    // Quota label for display
    public function getQuotaLabelAttribute(): string
    {
        return 'Open to All';
    }

    // Check if premises is occupied
    public function getIsOccupiedAttribute(): bool
    {
        return $this->premises_status === 'occupied';
    }

    // In app/Models/Premises.php
    public function hasApplicationHistory(): bool
    {
        return Application::withTrashed()->where('premises_id', $this->premises_id)->exists();
    }

    // ── Reject all pending applications for this premises ──
    public function rejectPendingApplications(int $excludedResidentId = null): int
    {
        $query = Application::where('premises_id', $this->premises_id)->where('application_status', 'pending');

        if ($excludedResidentId) {
            $query->where('resident_id', '!=', $excludedResidentId);
        }

        $pendingApps = $query->get();
        $count = 0;

        foreach ($pendingApps as $app) {
            $app->update([
                'application_status' => 'rejected',
                'remarks' => 'This premises has been rented to another applicant. The premises is no longer available.',
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

            NotificationService::applicationRejected($app, 'This premises has been rented to another applicant. The premises "' . $this->premises_name . '" is no longer available.');
            $count++;
        }

        return $count;
    }

    // Scopes

    // Scope: only available premises
    public function scopeAvailable($query)
    {
        return $query->where('premises_status', 'available');
    }
}
