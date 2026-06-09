<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'message',
        'company_domain',
        'company_size',
        'company_industry',
        'enrichment_status',
        'status',
        'source',
        'welcome_email_sent_at',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'company_size' => 'integer',
        'welcome_email_sent_at' => 'datetime',
    ];

    public const STATUSES = ['new', 'contacted', 'qualified', 'disqualified'];
    public const ENRICHMENT_STATUSES = ['pending', 'enriched', 'failed'];
}
