<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';
    protected $primaryKey = 'application_id';
    public $timestamps = false;

    protected $fillable = ['resident_id', 'premises_id', 'intended_business_type', 'financial_position', 'application_status', 'application_date', 'reviewed_by', 'reviewed_at', 'remarks'];

    protected $casts = [
        'application_date' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id', 'resident_id');
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

    // Accessor: status badge CSS class
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
