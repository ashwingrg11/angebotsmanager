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
    \App\Console\Commands\Inspire::class,
    \App\Console\Commands\LogDemo::class,
    \App\Console\Commands\SendEmails::class,
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    /*$schedule->command('inspire')
             ->hourly();*/
    // $schedule->command('log:demo')->everyMinute();
    // $schedule->command('emails:send')->everyMinute();
    $schedule->command('emails:send');
              // ->dailyAt('11:05')
              // ->mondays()
              // ->tuesdays()
              // ->wednesdays()
              // ->thursdays()
              // ->fridays();
              // ->dailyAt('12:00')->withoutOverlapping();
  }
}
