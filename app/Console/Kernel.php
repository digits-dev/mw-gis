<?php

namespace App\Console;

use App\Http\Controllers\AdminPulloutController;
use App\Http\Controllers\EBSPullController;
use App\Http\Controllers\ItemsController;
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
        $schedule->command('mysql:backup')->daily()->at('06:00');

        //$schedule->call('\App\Http\Controllers\EBSPullController@salesOrderPullHQ')->everyMinute();
        //$schedule->call('\App\Http\Controllers\AdminPulloutReceivingController@closeReceiving')->dailyAt('23:00');
        
        $schedule->call(function(){
            $onHandPull = new EBSPullController();
            $onHandPull->getRetailOnhand();
            $onHandPull->getLazadaOnhand();
            $onHandPull->getShopeeOnhand();
            $onHandPull->getDistriOnhand();
        })->dailyAt('04:00');

        $schedule->call(function(){
            $oraclePull = new EBSPullController();
            $oraclePull->getReceivedDistriSORTransactions();
            $oraclePull->getReceivedSORFBDTransactions();
            $oraclePull->getReceivedDOTDistri();
            $oraclePull->getReceivedSORFranchiseTransactions();

        })->dailyAt('23:00');

        $schedule->call(function(){
            $oraclePull = new EBSPullController();
            $oraclePull->getReceivedSORGisMwTransactions();
            $oraclePull->getReceivedMORGISSTWTransactions();
            $oraclePull->getReceivedSORTransactions();
            $oraclePull->getReceivedDOTTransactions();
            
        })->dailyAt('22:00');

        $schedule->call(function(){
            $pullouts = new AdminPulloutController();
            $pullouts->updateSORNumber();
            $pullouts->updateMwGisSORNumber();

            $oraclePull = new EBSPullController();
            $oraclePull->getDOTTransactions();
            $oraclePull->getMORTransactions();
            $oraclePull->moveOrderPullDistri();
            $oraclePull->getMORTransactions();
            
        })->everyFiveMinutes();

        $schedule->call(function(){
            $oraclePull = new EBSPullController();
            $oraclePull->moveOrderGboPull();
            $oraclePull->moveOrderPullRma();
            $oraclePull->moveOrderPull();
            $oraclePull->moveOrderPullOnline();
            $oraclePull->updateSerializedItems();

        })->everyThirtyMinutes();

        $schedule->call(function(){
            $itemSync = new ItemsController();
            $itemSync->getItemsCreatedAPI();
            $itemSync->getItemsUpdatedAPI();
            $itemSync->middlewareUpdateBEAItem();

            $oraclePull = new EBSPullController();
            $oraclePull->getWrr();
            $oraclePull->salesOrderPull();
            $oraclePull->salesOrderPullAdmin();

        })->hourly();

        // Auto Reject
        // $schedule->call('\App\Http\Controllers\AdminStoreTransferController@autoRejectHandCarry')->dailyAt('22:00');
        // $schedule->call('\App\Http\Controllers\AdminStoreTransferController@autoRejectLogistics')->dailyAt('22:00');

        // Auto create items
        // $schedule->call('\App\Http\Controllers\ItemsController@pushPOSItemCreation')->everyFiveMinutes();
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
