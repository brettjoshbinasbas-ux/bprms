<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalAgreement extends Model
{
    protected $table = 'rental_agreements';
    protected $primaryKey = 'agreement_id';
    public $timestamps = false;

    protected $fillable = ['application_id', 'payment_id', 'agreement_start_date', 'agreement_end_date', 'agreement_status', 'signed_at'];

    protected $casts = [
        'agreement_start_date' => 'date',
        'agreement_end_date' => 'date',
        'signed_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }

    // Accessor: status badge CSS class
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->agreement_status) {
            'active' => 'badge-active',
            'expired' => 'badge-expired',
            'terminated' => 'badge-terminated',
            default => 'bg-secondary',
        };
    }
}
