<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    public $timestamps = false;

    protected $fillable = ['resident_id', 'type', 'title', 'message', 'is_read', 'related_id', 'created_at'];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id', 'resident_id');
    }

    // ── Accessors ────────────────────────────────────────────────

    // Icon per notification type
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'vacancy_announcement' => 'bi-megaphone-fill',
            'application_approved' => 'bi-check-circle-fill',
            'application_rejected' => 'bi-x-circle-fill',
            'application_cancelled' => 'bi-dash-circle-fill',
            default => 'bi-bell-fill',
        };
    }

    // Color per type
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'vacancy_announcement' => '#1565c0',
            'application_approved' => '#2e7d32',
            'application_rejected' => '#c62828',
            'application_cancelled' => '#6a1b9a',
            default => '#616161',
        };
    }

    // Background color per type
    public function getBgAttribute(): string
    {
        return match ($this->type) {
            'vacancy_announcement' => '#e3f2fd',
            'application_approved' => '#e8f5e9',
            'application_rejected' => '#ffebee',
            'application_cancelled' => '#f3e5f5',
            default => '#fafafa',
        };
    }

    // ── Scopes ───────────────────────────────────────────────────

    // Notifications visible to a specific resident:
    // personal (their resident_id) OR broadcast (resident_id IS NULL)
    public function scopeForResident($query, int $residentId)
    {
        return $query->where(function ($q) use ($residentId) {
            $q->where('resident_id', $residentId)->orWhereNull('resident_id');
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    public function scopeBroadcasts($query)
    {
        return $query->whereNull('resident_id');
    }
}
