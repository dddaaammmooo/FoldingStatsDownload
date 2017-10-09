<?php

namespace App\Console;

use App\Console\Commands\StatsCleanup;
use App\Console\Commands\StatsDownload;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        StatsDownload::class,
        StatsCleanup::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        // Perform hourly stats download

        $schedule->command('stats:download')
            ->hourly()
            ->after(function () {
                // After the download has completed, perform cleanup

                Artisan::call('stats:cleanup');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
