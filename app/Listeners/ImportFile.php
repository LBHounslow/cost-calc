<?php

namespace App\Listeners;

// use App\Jobs\ImportSHBE;
use App\Jobs\ImportTempAccom;
use App\Jobs\ImportAdultSocialCareServices;
use App\Events\UploadFile;

class ImportFile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UploadFile $event
     * @return void
     */
    public function handle(UploadFile $event)
    {
        /*
                if ($event->uploadedFile['filetype'] == 'h01') {
                    dispatch(new ImportTempAccom($event));
                } elseif ($event->uploadedFile['filetype'] == 'asc01') {
                    dispatch(new ImportAdultSocialCareServices($event));
                } elseif ($event->uploadedFile['filetype'] == 'rb01') {
                    dispatch(new ImportSHBE($event));
                }
        */
    }
}
