<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class DatabaseBackupController extends Controller
{
    public function index()
    {
        $files = scandir(public_path('storage/backup'));

        $filePath = public_path('storage/backup/');
        
        return view('database-backup.database-backup', compact('files', 'filePath'));
    }

    public function download($file)
    {
        $download_file = public_path('storage/backup/'.$file);

        if(! empty($file)) {
            // Check file is exists on given path.
            if(file_exists($download_file)) {
                header('Content-Disposition: attachment; filename='.$file);

                readfile($download_file);

                exit;
            } else {
                echo 'File does not exists on given path';
            }
        }
    }

    public function manualBackup()
    {
        Artisan::call('database:backup');

        return back();
    }

    public function delete($file): RedirectResponse
    {
        $unlink = public_path('storage/backup/'.$file);

        unlink($unlink);

        return back();
    }
}
