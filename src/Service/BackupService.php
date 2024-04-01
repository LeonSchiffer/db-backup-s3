<?php
namespace BishalGurung\Backup\Service;

use ZipArchive;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    public function getBackupFileNames(): Collection
    {
        $files = Storage::disk("s3")->allFiles(config("backup.backup.name"));
        return collect($files);
    }

    public function putS3FileToStorage($path)
    {
        $file = Storage::disk("s3")->get($path);
        $temp_path = "backup-temp/" . basename($path);
        Storage::put($temp_path, $file);
        return $temp_path;
    }

    public function extractingTempZipFile($temp_path)
    {
        $zip = new ZipArchive;
        if ($zip->open(Storage::path($temp_path))) {
            $zip->extractTo(Storage::path("backup-temp"));
            $zip->close();
        }
    }

    public function importDb()
    {
        $file_name = Storage::allFiles("backup-temp/db-dumps")[0];
        $query = File::get(Storage::path($file_name));
        DB::unprepared($query);
    }

    public function checkConfigVariablesAreSet()
    {
        $variables = collect([
            "AWS_ACCESS_KEY_ID" => env("AWS_ACCESS_KEY_ID"),
            "AWS_SECRET_ACCESS_KEY" => env("AWS_SECRET_ACCESS_KEY"),
            "AWS_DEFAULT_REGION" => env("AWS_DEFAULT_REGION"),
            "AWS_BUCKET" => env("AWS_BUCKET"),
            "BACKUP_FOLDER_NAME" => config("backup.backup.name")
        ]);
        $not_set_variables = $variables->filter(fn($var) => !$var);
        return $not_set_variables;
    }
}
