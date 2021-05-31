<?php

namespace App\Console\Commands;

use App\Managers\DatabaseBackupManager;
use Exception;
use Illuminate\Console\Command;

class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command is responsible for database backup';

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
     * @return void
     */
    public function handle()
    {
        $this->info('Database started exporting..');

        $file = '';
        try {
            $file = resolve(DatabaseBackupManager::class)
                ->export();

            $this->info('Database export successfully.');

            $this->info('Exported file go to zip.');

            resolve(DatabaseBackupManager::class)
                ->zip($file);

            $this->info('The backup has been finished successfully.');
        } catch (Exception $e) {
            unlink($file);

            $this->error(
                'The backup process failed with: '.$e->getMessage()
            );
        }
    }
}
