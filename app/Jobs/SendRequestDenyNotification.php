<?php

namespace App\Jobs;

use App\Mail\RequestDenyNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRequestDenyNotification implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable;

    public $user;
    public $balanceReq;
    public $message;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $balanceReq, $message)
    {
        $this->user = $user;
        $this->balanceReq = $balanceReq;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->user->email)
            ->send(new RequestDenyNotification($this->user,$this->balanceReq,$this->message));
    }
}
