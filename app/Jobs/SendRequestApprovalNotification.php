<?php

namespace App\Jobs;

use App\Mail\RequestApprovalNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRequestApprovalNotification implements ShouldQueue
{
    use Queueable, SerializesModels,Dispatchable;

    public $user;
    public $balanceREQ;
    public $description;
    public $installments;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $balanceREQ,$description, $installments)
    {
        $this->user = $user;
        $this->balanceREQ = $balanceREQ;
        $this->description = $description;
        $this->installments = $installments;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->user->email)
            ->send(new RequestApprovalNotification($this->user, $this->balanceREQ, $this->description, $this->installments));
    }
}
