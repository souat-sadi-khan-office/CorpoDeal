<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DeleteOldActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete activity logs older than the configured number of days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = (int)get_settings('activity_log_delete_day') ?? 30;

        if ($days <= 0) {
            $this->error('Invalid "activity_log_delete_day" value. It must be greater than 0.');
            return Command::FAILURE;
        }

        $cutoffDate = Carbon::now()->subDays($days);

        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        if ($deletedCount > 0) {
            // Create a system activity log
            ActivityLog::create([
                'user_id' => null,
                'admin_id' => null,
                'activity_id' => null,
                'activity_type' => 'system',
                'activity' => "Deleted $deletedCount activity log(s) older than $days day(s).",
                'action' => 'delete',
            ]);
            $this->info("Successfully deleted $deletedCount activity log(s) older than $days day(s).");
        }

        return Command::SUCCESS;
    }
}
