# Instructions

## Installation

- composer require bishalgurung/db-backup-s3:dev-main
- php artisan vendor:publish --provider="BishalGurung\Backup\BackupS3ServiceProvider"
- setup the following credentials in .env and backup.php: AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION, AWS_BUCKET, BACKUP_FOLDER_NAME
- In backup.php, "name" should be unique for every project
- Use **_php artisan backup:db-s3_** to backup your database
- Use **_php artisan backup:import-s3 {path}_** to import data from s3 (Keep in mind that this will delete all data from your current database)
