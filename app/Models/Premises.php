<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Premises extends Model
{
    protected $table = 'premises';
    protected $primaryKey = 'premises_id';
    // only created_at and updated_at but updated_at is ON UPDATE NOW() in DB
    // we manage manually
    public $timestamps = false;

    protected $fillable = ['location_id', 'premises_name', 'premises_type', 'premises_description', 'rental_fee', 'premises_status'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'premises_id', 'premises_id');
    }

    // Accessor: formatted type label
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

    // Accessor: status badge CSS class
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->premises_status) {
            'available' => 'badge-available',
            'occupied' => 'badge-occupied',
            'unavailable' => 'badge-unavailable',
            default => 'bg-secondary',
        };
    }

    // Scope: only available premises
    public function scopeAvailable($query)
    {
        return $query->where('premises_status', 'available');
    }
}
