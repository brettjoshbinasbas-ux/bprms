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

    protected $fillable = ['resident_first_name', 'resident_middle_name', 'resident_last_name', 'resident_ic_number', 'resident_phone', 'resident_address_line1', 'resident_address_line2', 'resident_postcode', 'resident_city', 'resident_state', 'resident_email', 'resident_password', 'residency_duration', 'marital_status', 'mdch_license_holder', 'business_experience', 'business_type'];

    protected $hidden = ['resident_password'];

    protected $casts = [
        'mdch_license_holder' => 'boolean',
        'business_experience' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // ── Boot: cascade soft deletes to applications ─────────────────
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($resident) {
            if ($resident->isForceDeleting()) {
                $resident->applications()->forceDelete();
            } else {
                $resident->applications()->delete();
            }
        });

        static::restoring(function ($resident) {
            $resident->applications()->withTrashed()->restore();
        });
    }

    // ── Auth ───────────────────────────────────────────────────────
    public function getAuthPassword(): string
    {
        return $this->resident_password;
    }

    // ── Accessors ──────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim($this->resident_first_name . ' ' . ($this->resident_middle_name ? $this->resident_middle_name . ' ' : '') . $this->resident_last_name);
    }

    // Full formatted address — used in views wherever the complete
    // address needs to be shown as a single readable string
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->resident_address_line1, $this->resident_address_line2, $this->resident_postcode . ' ' . $this->resident_city, $this->resident_state]);
        return implode(', ', $parts);
    }

    // ── Relationships ──────────────────────────────────────────────

    public function applications()
    {
        return $this->hasMany(Application::class, 'resident_id', 'resident_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'resident_id', 'resident_id');
    }

    // ── Business logic ─────────────────────────────────────────────

    public function hasActiveAgreement(): bool
    {
        return Application::where('resident_id', $this->resident_id)->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'))->exists();
    }
}
