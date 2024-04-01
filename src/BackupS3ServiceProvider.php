<?php

namespace BishalGurung\Backup;

use Illuminate\Support\ServiceProvider;
use BishalGurung\Backup\Console\BackupDbToS3Command;
use BishalGurung\Backup\Console\ImportFromS3Command;

class BackupS3ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            BackupDbToS3Command::class,
            ImportFromS3Command::class
        ]);

        $this->registerPublishing();
    }

    public function boot()
    {

    }

    public function registerPublishing()
    {
        $this->publishes([
            __DIR__ . "/config/backup.php" => config_path("backup.php")
        ], "backup-s3-config");
    }
}