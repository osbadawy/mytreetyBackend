<?php

namespace App\Console;

use App\Jobs\CalculateLevelJob;
use App\Jobs\CalculateScoreJob;
use App\Jobs\ShopifyDailyJob;
use App\Jobs\XMLSyncDailyJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // Auto sync shopify shops
        $schedule->job(new ShopifyDailyJob)->Daily();

        // Auto sync xml files
        // $schedule->job(new XMLSyncDailyJob)->Daily();

        // Update score for needed products
        $schedule->job(new CalculateScoreJob)->Daily();

        // Update icons score and update level of all products
        $schedule->job(new CalculateLevelJob)->Daily();


        $schedule->command('telescope:prune')->Daily();



        $schedule->command('queue:work --stop-when-empty')->withoutOverlapping();

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
