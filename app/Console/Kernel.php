<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use PDO;
use DB;
use Yajra\Pdo\Oci8\Statement;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\DatabaseBackup',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        $schedule->call('\App\Http\Controllers\EBSPullController@getReceivedSORTransactions')->dailyAt('22:00');
        $schedule->call('\App\Http\Controllers\EBSPullController@getReceivedDOTTransactions')->dailyAt('22:20');
        $schedule->call('\App\Http\Controllers\EBSPullController@getReceivedSORFranchiseTransactions')->dailyAt('22:40');
        $schedule->call('\App\Http\Controllers\EBSPullController@getReceivedDistriSORTransactions')->dailyAt('23:00');
        $schedule->call('\App\Http\Controllers\EBSPullController@getReceivedSORFBDTransactions')->dailyAt('23:15');
        $schedule->call('\App\Http\Controllers\EBSPullController@getReceivedDOTDistri')->dailyAt('23:40');
        
        $schedule->call('\App\Http\Controllers\EBSPullController@getRetailOnhand')->dailyAt('04:05'); //04:05
        $schedule->call('\App\Http\Controllers\EBSPullController@getLazadaOnhand')->dailyAt('04:35'); //04:35
        $schedule->call('\App\Http\Controllers\EBSPullController@getShopeeOnhand')->dailyAt('05:05'); //05:05
        $schedule->call('\App\Http\Controllers\EBSPullController@getDistriOnhand')->dailyAt('05:35');
        
        $schedule->command('mysql:backup')->daily()->at('06:00');
        
        $schedule->call('\App\Http\Controllers\ItemsController@middlewareUpdateBEAItem')->dailyAt('07:10');
        $schedule->call('\App\Http\Controllers\ItemsController@getItemsCreatedAPI')->hourly();
        $schedule->call('\App\Http\Controllers\ItemsController@getItemsUpdatedAPI')->hourly();
        
        $schedule->call('\App\Http\Controllers\EBSPullController@getWrr')->hourly();
        //$schedule->call('\App\Http\Controllers\AdminPulloutReceivingController@closeReceiving')->dailyAt('23:00');
        
        $schedule->call('\App\Http\Controllers\EBSPullController@salesOrderPull')->hourly(); //hourly
        $schedule->call('\App\Http\Controllers\EBSPullController@salesOrderPullAdmin')->hourly(); //hourly
            
        $schedule->call('\App\Http\Controllers\EBSPullController@getDOTTransactions')->everyFiveMinutes();
        $schedule->call('\App\Http\Controllers\EBSPullController@getMORTransactions')->everyFiveMinutes();

        //-- $schedule->call('\App\Http\Controllers\EBSPullController@salesOrderPullHQ')->everyMinute();
        
        $schedule->call('\App\Http\Controllers\EBSPullController@moveOrderPullRma')->everyThirtyMinutes();
        $schedule->call('\App\Http\Controllers\EBSPullController@moveOrderPull')->everyThirtyMinutes();
        $schedule->call('\App\Http\Controllers\EBSPullController@moveOrderPullOnline')->everyThirtyMinutes();
        $schedule->call('\App\Http\Controllers\EBSPullController@moveOrderPullDistri')->everyFiveMinutes();
        $schedule->call('\App\Http\Controllers\EBSPullController@updateSerializedItems')->everyThirtyMinutes();
        $schedule->call('\App\Http\Controllers\AdminPulloutController@updateSORNumber')->everyFiveMinutes();
        
        //2022-05-25
        
        
        // $schedule->call('\App\Http\Controllers\ItemsController@pushPOSItemCreation')->everyFiveMinutes();
        //2022-05-25
        
        // $schedule->call('\App\Http\Controllers\ItemsController@pushBEAItemCreation')->hourly();
        
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
