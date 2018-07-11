<?php

namespace App\Console;

use App\Http\Services\BinanceService;
use App\Http\Services\WantService;
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

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         // macd
//        $schedule->call(function () {
//           for ($i=0; $i<19;$i++) {
//               $res = BinanceService::tradeBtc2();
////               \Log::debug('macd '.$res);
//               sleep(2);
//           }
//        })->everyMinute();

          // 标记k线连续涨跌
//         $schedule->call(function () {
//              WantService::markPriceChange('fcoin', 'ftusdt', 'M1');
//         })->everyMinute();
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
