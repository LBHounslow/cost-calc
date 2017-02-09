<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Upload_log;
use App\Jobs\ImportTempAccom;
use App\Jobs\ImportAdultSocialCareServices;

class ImportFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the first file that need to be processed in the Upload Log';

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
        // 1 - get first file to be processed
        $uploadedFile = Upload_log::where('processed', 0)->first();

        // 2 - work out which import script to run
        if (isset($uploadedFile->filetype)) {

            if ($uploadedFile->filetype == 'h01') {
                dispatch(new ImportTempAccom($uploadedFile));
            } elseif ($uploadedFile->filetype == 'asc01') {
                dispatch(new ImportAdultSocialCareServices($uploadedFile));
            }
        }

        $this->info('Finished');

    }
}
