<?php

namespace App\Jobs;

use App\Mail\AdminCustomerCreate;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAdminCustomerCreateMail implements ShouldQueue
{
    use Queueable, Dispatchable;

    public $user;
    public $password;
    public $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new AdminCustomerCreate($this->user,$this->password));
    }
}
