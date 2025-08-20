<?php

namespace App\Jobs;

use App\CPU\Helpers;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class SendBatchEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subject;
    protected $message;
    protected $attachments;
    public $tries = 3;
    public $sleep = 5;

    /**
     * Create a new job instance.
     *
     * @param string $subject
     * @param string $message
     * @param mixed|null $attachments
     */
    public function __construct(string $subject, string $message, ?array $attachments = [])
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $users = User::where('status', 1)->get();

        if ($users->isEmpty()) {
            Helpers::activity(
                null,
                null,
                null,
                'system',
                'No active users found to send batch emails with subject <strong>' . e($this->subject) . '</strong>.',
                'default'
            );
            return;
        }
        // Log the number of users and the subject before starting the batch
        Helpers::activity(null,null,null,'system', 'Preparing to send batch emails to ' . $users->count() . ' active users with subject "' . e($this->subject) . '".','default');
        // Prepare jobs for batch
        $jobs = $users->map(function ($user) {
            return (new SendEmailToUser($user, $this->subject, $this->message, $this->attachments))->delay(now()->addSeconds(rand(1, 7)));
        });

        $subject = $this->subject; // Capture subject for batch activity

        // Dispatch batch
        Bus::batch($jobs)
            ->onConnection('database')
            ->onQueue('medium')
            ->name('SendBatchEmail-' . e($subject))
            ->before(function (Batch $batch) use ($subject) {
                Helpers::logBatchActivity($subject, 'start');
            })
            ->then(function (Batch $batch) use ($subject) {
                Helpers::logBatchActivity($subject, 'success');
            })
            ->catch(function (Batch $batch, Throwable $e) use ($subject) {
                Helpers::logBatchActivity($subject, 'failure', $e->getMessage(),$batch->id);
            })
            ->finally(function (Batch $batch) use ($subject) {
                Helpers::logBatchActivity($subject, 'complete');
                session()->flash('notification', [
                    'type' => 'success',
                    'message' => 'Batch emails have been sent successfully with subject: ' . $subject,
                ]);
            })
            ->dispatch(); // Use dispatchSync instead of dispatch
    }
}
