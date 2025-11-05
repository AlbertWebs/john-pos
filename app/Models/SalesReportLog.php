<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReportLog extends Model
{
    protected $fillable = [
        'report_date',
        'report_type',
        'summary',
        'file_path',
        'recipient_email',
        'sent',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'sent' => 'boolean',
            'sent_at' => 'datetime',
        ];
    }
}
