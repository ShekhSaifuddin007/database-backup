<?php

namespace App\Managers;

use ZipArchive;
use Carbon\Carbon;

class DatabaseBackupManager
{
    public function export(): string
    {
        $command = $this->command();

        $returnVar = null;
        $output = null;

        exec($command[0], $output, $returnVar);

        return $command[1];
    }

    protected function command(): array
    {
        $filePath = $this->filePath();

        $dumpPath = config('dbbackup.windows_dump_path'); // windows dump path

        if (windows_os()) {
            $command = "{$dumpPath}mysqldump --user=".config('database.connections.mysql.username')." --password=".config('database.connections.mysql.password')." --host=".config('database.connections.mysql.host')." ".config('database.connections.mysql.database')." > ".$filePath;
        } else {
            $command = "mysqldump --column-statistics=0 --user=".config('database.connections.mysql.username')." --password=".config('database.connections.mysql.password')." --host=".config('database.connections.mysql.host')." ".config('database.connections.mysql.database')." > ".$filePath;
        }

        return [
            $command,
            $filePath
        ];
    }

    protected function filePath(): string
    {
        $date = Carbon::now()->format('d-M-Y').'-'.time();

        $filename = config('dbbackup.filename_prefix') !== null
            ? config('database.connections.mysql.database').'-'.config('dbbackup.filename_prefix')."-{$date}.sql"
            : config('database.connections.mysql.database')."-backup-{$date}.sql";

        $path = storage_path(
            'app'. DIRECTORY_SEPARATOR .
            'db-temp'. DIRECTORY_SEPARATOR
        );

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        return $path.$filename;
    }

    public function zip($filePath)
    {
        $zip = new ZipArchive;

        $filename = pathinfo($filePath, PATHINFO_FILENAME);

        $fileToZip = $filePath.'.zip';

        if ($zip->open($fileToZip, ZipArchive::CREATE) != TRUE) {
            die ("Could not open archive");
        }

        $zip->addFile($filePath, $filename.'.sql');

        $zip->close();

        unlink($filePath);

        sleep(1);

        $this->move();
    }

    protected function move()
    {
        $fromFolder = storage_path('app'. DIRECTORY_SEPARATOR .'db-temp'. DIRECTORY_SEPARATOR);

        $file = scandir($fromFolder)[2];

        $from = $fromFolder.$file;

        $toFolder = storage_path('app'. DIRECTORY_SEPARATOR .'public'. DIRECTORY_SEPARATOR .'backup'. DIRECTORY_SEPARATOR);

        if (! is_dir($toFolder)) {
            mkdir($toFolder, 0755, true);
        }

        $to = $toFolder.$file;

        copy($from, $to);
        unlink($from);
    }
}