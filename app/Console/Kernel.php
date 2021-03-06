<?php

namespace App\Console;

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
    '\App\Console\Commands\DeleteOldOffers',
    '\App\Console\Commands\RemindUser',
    '\App\Console\Commands\BatchNotify',
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('DeleteOldOffers:deleteoffers')->everyTenMinutes();
    $schedule->command('RemindUser:reminduser')->everyFiveMinutes();
    $schedule->command('BatchNotify:notify')->hourly();
  }

  /**
   * Register the Closure based commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    require base_path('routes/console.php');
  }
}
