<?php

namespace App\Console;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\MessageQueue;
use App\Jobs\SendMessageJob;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
     protected $commands = [
        Commands\DemoCron::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
   protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        $pendingMessages = MessageQueue::where('message_status', 0)->get();
        Log::info("✅ TestJob is running!");

        foreach ($pendingMessages as $message) {
          
            SendMessageJob::dispatch($message);
        }
    })->everyMinute(); // हर मिनट चलेगा
}

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
