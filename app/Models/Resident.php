<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Resident extends Authenticatable
{
    protected $table = 'residents';
    protected $primaryKey = 'resident_id';
    public $timestamps = false;

    protected $fillable = ['resident_first_name', 'resident_middle_name', 'resident_last_name', 'resident_ic_number', 'resident_phone', 'resident_address', 'resident_email', 'resident_password', 'residency_duration', 'marital_status', 'mdch_license_holder', 'business_experience', 'business_type'];

    protected $hidden = ['resident_password'];

    protected $casts = [
        'mdch_license_holder' => 'boolean',
        'business_experience' => 'boolean',
    ];

    // Required by Laravel auth
    public function getAuthPassword(): string
    {
        return $this->resident_password;
    }

    // Accessor: full name
    public function getFullNameAttribute(): string
    {
        return trim("{$this->resident_first_name} " . ($this->resident_middle_name ? "{$this->resident_middle_name} " : '') . $this->resident_last_name);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'resident_id', 'resident_id');
    }
}
