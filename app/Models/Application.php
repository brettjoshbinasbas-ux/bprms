<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $table = 'applications';
    protected $primaryKey = 'application_id';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $fillable = ['resident_id', 'premises_id', 'intended_business_type', 'financial_position', 'application_status', 'application_date', 'reviewed_by', 'reviewed_at', 'remarks'];

    protected $casts = [
        'application_date' => 'datetime',
        'reviewed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships 

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id', 'resident_id')->withTrashed(); // keep visible even if resident is soft-deleted
    }

    public function premises()
    {
        return $this->belongsTo(Premises::class, 'premises_id', 'premises_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by', 'admin_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'application_id', 'application_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'application_id', 'application_id');
    }

    public function rentalAgreement()
    {
        return $this->hasOne(RentalAgreement::class, 'application_id', 'application_id');
    }

    // Accessors 

    // Original status badge (kept for backward compatibility)
    // For admin view
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->application_status) {
            'pending' => 'badge-pending',
            'approved' => 'badge-approved',
            'rejected' => 'badge-rejected',
            'cancelled' => 'badge-cancelled',
            default => 'bg-secondary',
        };
    }

    // Derives a combined display status factoring in the rental agreement
    // so resident views show 'Active' or 'Terminated' instead of just 'Approved'
    public function getDisplayStatusAttribute(): string
    {
        if ($this->application_status === 'approved' && $this->rentalAgreement) {
            return match ($this->rentalAgreement->agreement_status) {
                'active' => 'active',
                'terminated' => 'terminated',
                'expired' => 'expired',
                default => $this->application_status,
            };
        }
        return $this->application_status;
    }

    // For resident view
    public function getDisplayStatusBadgeClassAttribute(): string
    {
        return match ($this->display_status) {
            'pending' => 'badge-pending',
            'approved' => 'badge-approved',
            'active' => 'badge-active',
            'rejected' => 'badge-rejected',
            'cancelled' => 'badge-cancelled',
            'terminated' => 'badge-terminated',
            'expired' => 'badge-expired',
            default => 'bg-secondary',
        };
    }

    // Scopes

    public function scopePending($query)
    {
        return $query->where('application_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('application_status', 'approved');
    }

    public function scopeByResident($query, $residentId)
    {
        return $query->where('resident_id', $residentId);
    }
}
