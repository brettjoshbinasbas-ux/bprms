<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'residents';
    protected $primaryKey = 'resident_id';
    public $timestamps = false;

    protected $fillable = ['resident_first_name', 'resident_middle_name', 'resident_last_name', 'resident_ic_number', 'resident_phone', 'resident_address', 'resident_email', 'resident_password', 'residency_duration', 'marital_status', 'mdch_license_holder', 'business_experience', 'business_type'];

    protected $hidden = ['resident_password'];

    protected $casts = [
        'mdch_license_holder' => 'boolean',
        'business_experience' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // ── Boot method to handle cascading soft deletes ──
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($resident) {
            // When resident is soft deleted, also soft delete all their applications
            if ($resident->isForceDeleting()) {
                // Force delete - also force delete applications
                $resident->applications()->forceDelete();
            } else {
                // Soft delete - also soft delete applications
                $resident->applications()->delete();
            }
        });

        static::restoring(function ($resident) {
            // When resident is restored, also restore all their applications
            $resident->applications()->withTrashed()->restore();
        });
    }

    // Required by Laravel auth
    public function getAuthPassword(): string
    {
        return $this->resident_password;
    }

    // Accessor: full name
    public function getFullNameAttribute(): string
    {
        return trim($this->resident_first_name . ' ' . ($this->resident_middle_name ? $this->resident_middle_name . ' ' : '') . $this->resident_last_name);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'resident_id', 'resident_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'resident_id', 'resident_id');
    }

    // Check if this resident currently has an active rental agreement.
    public function hasActiveAgreement(): bool
    {
        return Application::where('resident_id', $this->resident_id)->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'))->exists();
    }
}
