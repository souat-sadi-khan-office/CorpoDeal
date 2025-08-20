<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'code',
        'full_name',
        'email',
        'phone',
        'subject',
        'details',
        'file_one',
        'file_two',
        'file_three',
        'file_four',
        'file_five',
        'is_replied',
        'is_viewed',
    ];

    // Relationship with SupportTicketReply
    public function reply()
    {
        return $this->belongsTo(SupportTicketReply::class);
    }
}
