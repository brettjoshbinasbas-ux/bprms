<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    protected $fillable = ['admin_first_name', 'admin_middle_name', 'admin_last_name', 'admin_email', 'admin_password'];

    protected $hidden = ['admin_password'];

    // Required by Laravel auth — maps to the password field
    public function getAuthPassword(): string
    {
        return $this->admin_password;
    }

    // Accessor: full name
    public function getFullNameAttribute(): string
    {
        return trim("{$this->admin_first_name} " . ($this->admin_middle_name ? "{$this->admin_middle_name} " : '') . $this->admin_last_name);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'reviewed_by', 'admin_id');
    }
}
