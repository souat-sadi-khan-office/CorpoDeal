<?php

namespace App\Jobs;

use App\CPU\Helpers;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\BatchEmail;

class SendEmailToUser implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels, Dispatchable, Batchable;

    protected $user;
    protected $subject;
    protected $message;
    protected $attachments;
    public $tries = 7;
    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $subject
     * @param string $message
     * @param string $attachments
     */
    public function __construct(User $user, $subject, $message, $attachments = null)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->message = $message;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Send the email to the user
        Mail::to($this->user->email)
            ->send(new BatchEmail($this->user, $this->subject, $this->message, $this->attachments));
    }

    /**
     * Handle the batch ID assignment.
     *
     * @param string $batchId
     */
    public function withBatchId($batchId)
    {
        // This method is called to associate the job with a specific batch ID
        $this->batchId = $batchId;
    }

    /**
     * Exponential backoff strategy for retries.
     *
     * @return array
     */
    public function backoff(): array
    {
        return [1, 5, 10, 15, 20, 30, 60];
    }

    /**
     * Handle the job failure.
     *
     * @param \Throwable $exception
     */
    public function failed(\Throwable $exception)
    {
        // Log the failure or take other action
        Helpers::activity(
            null,
            null,
            null,
            'system',
            'Failed to send email to user ' . $this->user->email . '. Error: ' . $exception->getMessage(),
            'default'
        );
    }

}
