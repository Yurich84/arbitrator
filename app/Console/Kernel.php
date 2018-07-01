<?php

namespace App\Console;

use App\Console\Commands;
use App\Models\Stock;
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
        Commands\My::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * EDITOR=nano crontab -e
     * set crontab * * * * * /usr/local/bin/php /Volumes/data/WEB/arbitrator/artisan schedule:run >> /dev/null 2>&1
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        if(config('bot.go')) {
            $active_stocks = Stock::where('active', 1)->get();

            foreach ($active_stocks as $stock) {
                // поиск вилок
                $schedule->command('arb:trio ' . $stock->ccxt_id)
                    ->cron('*/' . ($stock->timeout ?: config('bot.timeout')) . ' * * * *');

                // Дважди в день обновляем блеклист
                $schedule->command('arb:blacklist ' . $stock->ccxt_id)
                    ->twiceDaily(9, 16);
            }

            // удаляем старие записи
            $schedule->command('arb:clear')
                ->hourly();
        }

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
