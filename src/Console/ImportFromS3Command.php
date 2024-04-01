<?php

namespace BishalGurung\Backup\Console;

use Illuminate\Console\Command;
use BishalGurung\Backup\Service\BackupService;

class ImportFromS3Command extends Command
{
    protected $signature = "backup:import-s3 {path}";

    protected $description = "Imports the data from s3";

    public function handle()
    {
        $latest_backup_file = $this->argument("path");

        $backup_service = new BackupService;
        $not_set_variables = $backup_service->checkConfigVariablesAreSet();
        if ($not_set_variables->isNotEmpty()) {
            return $this->error("The following variables are not set: " . implode(", ", $not_set_variables->keys()->toArray()));
        }
        // $files = $backup_service->getBackupFileNames();

        // if (is_null($latest_backup_file)) {
        //     $this->info("Getting the latest backup file path...");
        //     $latest_backup_file = $files->sortDesc()->first();
        // }
        $this->info("The backup file name is " . $latest_backup_file);

        $this->info("Download the latest backup file to storage...");
        $temp_path = $backup_service->putS3FileToStorage($latest_backup_file);
        $this->info("Download Complete");

        $this->info("Extracting backup zip file...");
        $backup_service->extractingTempZipFile($temp_path);
        $this->info("Extracted");

        $this->call("db:wipe");
        $this->info("Importing Database...");
        $backup_service->importDb();
        $this->info("Database import complete!");
    }
}