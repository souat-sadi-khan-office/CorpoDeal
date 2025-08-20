<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketReply extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'ticket_id',
        'admin_id',
        'message',
        'file_one',
        'file_two',
        'file_three',
        'is_email_sent',
    ];

    // Relation with SupportTicket
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    // Relation with Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
