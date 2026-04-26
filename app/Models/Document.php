<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'document_id';
    public $timestamps = false;

    protected $fillable = ['application_id', 'document_type', 'document_filename', 'document_path'];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    // Accessor: human-readable document type label
    public function getTypeLabelAttribute(): string
    {
        return match ($this->document_type) {
            'ic_copy' => 'IC Copy',
            'applicant_photo' => 'Applicant Photo',
            'spouse_photo' => 'Spouse Photo',
            'supporting_document' => 'Supporting Document',
            default => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }
}
