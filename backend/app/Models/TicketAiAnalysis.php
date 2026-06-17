<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAiAnalysis extends Model
{
    protected $fillable = [
        'ticket_id',
        'category_suggested',
        'priority_suggested',
        'confidence',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];
}
