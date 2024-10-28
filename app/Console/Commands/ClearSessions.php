<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all session files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sessionPath = storage_path('framework/sessions');
        File::cleanDirectory($sessionPath);

        $this->info('Session files cleared successfully.');
    }
}
