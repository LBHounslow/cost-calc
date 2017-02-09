<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use Exception;
use Excel;

use Carbon\Carbon;
use App\AdultSocialCareServices;
use App\Upload_log;

class ImportAdultSocialCareServices implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $uploadedFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileId = $this->uploadedFile->id;
        $filePath = '/storage/app/' . $this->uploadedFile->path;

        Excel::selectSheetsByIndex(0)->load($filePath, function ($reader) use ($fileId) {
            $reader->each(function ($row) use ($fileId) {

                // check if it is an empty row
                foreach ($row as $column) {
                    if ($column == NULL) {
                        $emptyRow = true;
                    } else {
                        $emptyRow = false;
                        break;
                    }
                }

                if ($emptyRow == false) {

                    /* if end date is empty, set compare to today */
                    if (empty($row['end'])) {
                        $compareEnd = Carbon::today();
                    } else {
                        $compareEnd = $row['end'];
                    }

                    /* if end date is empty, set compare to today */
                    if (empty($row['priceend'])) {
                        $comparePriceEnd = Carbon::today();
                    } else {
                        $comparePriceEnd = $row['priceend'];
                    }

                    /* get date range of service use from service date and price date */
                    if ($row['pricestart'] > $row['start']) {
                        $startDate = $row['pricestart'];
                    } else {
                        $startDate = $row['start'];
                    }

                    /* get date range of service use from service date and price date */
                    if ($comparePriceEnd < $compareEnd) {
                        $endDate = $row['priceend'];
                    } else {
                        $endDate = $row['end'];
                    }

                    /* create the record */
                    AdultSocialCareServices::create([
                        'upload_id' => $fileId,
                        'asc_id' => $row['ascid'],
                        'address_1' => $row['address1'],
                        'address_2' => $row['address2'],
                        'address_3' => $row['address3'],
                        'town' => $row['town'],
                        'county' => $row['county'],
                        'postcode' => $row['postcode'],
                        'nhs_no' => $row['nhsno'],
                        'first_name' => $row['firstname'],
                        'surname' => $row['surname'],
                        'dob' => $row['dob'],
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'cost' => $row['cost'],
                        'frequency' => $row['frequency'],
                        'service' => $row['service'],
                        'service_type' => $row['servicetype'],
                        'primary_support_reason_category' => $row['primarysupportreasoncategory']
                    ]);

                } else {
                    // do nothing...
                }

            });

        });

        /* update the upload log record */
        $uploadLogRecord = Upload_log::find($fileId);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 1;
        $uploadLogRecord->save();

        /* match / insert the client */
        $sql = <<<EOT
INSERT INTO [dbo].[clients]
        (surname, dob, postcode, created_at, updated_at)

SELECT DISTINCT
        imp.surname, imp.dob, imp.postcode, GETDATE(), GETDATE()
FROM
        [dbo].[import_adult_social_care_services] imp
LEFT JOIN
        [dbo].[clients] c ON c.surname = imp.surname AND c.dob = imp.dob AND c.postcode = imp.postcode

WHERE
        c.id IS NULL
        AND imp.surname IS NOT NULL
        AND imp.dob IS NOT NULL
        AND imp.postcode IS NOT NULL;

UPDATE
        imp
SET
        imp.client_id = c.id
FROM
        [dbo].[import_adult_social_care_services] AS imp
INNER JOIN
        [dbo].[clients] AS c ON c.surname = imp.surname AND c.dob = imp.dob AND c.postcode = imp.postcode;
EOT;


        DB::unprepared(DB::raw($sql));

        $this->deleteFile();
    }


    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        /* add error message to upload log */
        $uploadLogRecord = Upload_log::find($this->uploadedFile->id);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 0;
        $uploadLogRecord->error_msg = $exception->getMessage();
        $uploadLogRecord->save();

        $this->deleteFile();
    }

    public function deleteFile()
    {
        Storage::delete($this->uploadedFile->path);
    }
}
