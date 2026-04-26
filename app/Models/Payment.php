<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = ['application_id', 'amount', 'card_number', 'card_expiry_date', 'payment_date', 'payment_status'];

    protected $casts = [
        'card_expiry_date' => 'date',
        'payment_date' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    public function rentalAgreement()
    {
        return $this->hasOne(RentalAgreement::class, 'payment_id', 'payment_id');
    }

    // Accessor: masked card number display
    public function getMaskedCardNumberAttribute(): string
    {
        return '**** **** **** ' . substr($this->card_number, -4);
    }

    // Accessor: payment status badge CSS
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'completed' => 'badge-approved',
            'pending' => 'badge-pending',
            'failed' => 'badge-rejected',
            default => 'bg-secondary',
        };
    }
}
