<?php

namespace BishalGurung\Backup\Console;

use Illuminate\Console\Command;

class BackupDbToS3Command extends Command
{
    protected $signature = "backup:db-s3";

    protected $description = "This backs up data to s3";

    public function handle()
    {
        $this->call("backup:run", [
            "--only-db" => true,
            "--only-to-disk" => "s3"
        ]);
    }
}