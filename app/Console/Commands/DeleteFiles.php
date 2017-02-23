<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploads:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all uploaded files that are older than 24 hours';

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
        // get list of files
        $files = Storage::allFiles('uploads');

        foreach ($files as $file) {

            // check if file was modified over 24 hours ago
            if (Storage::lastModified($file) < strtotime('-24 hours')) {
                
                // delete file
                Storage::delete($file);
            }

        }
    }
}
