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

                $mandatoryColumns = [
                    'postcode',
                    'dob',
                    'surname',
                    'start',
                    'servicetype',
                ];

                if ($this->checkMandatoryColumns($mandatoryColumns, $row) && $emptyRow === false) {

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

                    // check if we have record already
                    $existingRecord = AdultSocialCareServices::where([
                        ['surname', $row['surname'] ?? null],
                        ['dob', $row['dob'] ?? null],
                        ['postcode', $row['postcode'] ?? null],
                        ['start_date', $startDate ?? null],
                        ['care_package_line_item_id', $row['carepackagelineitemid'] ?? null],
                    ])->first();

                    if (isset($existingRecord->id)) {

                        if (empty($endDate)) {
                            // do nothing...
                        } else {

                            $newDate = new \Carbon\Carbon($endDate);

                            if (empty($existingRecord->end_date)) {
                                $existDate = new \Carbon\Carbon('1980-01-01');
                            } else {
                                $existDate = new \Carbon\Carbon($existingRecord->end_date);
                            }
                            if ($newDate->gt($existDate)) {
                                // update end date
                                $existingRecord->end_date = $endDate;
                                $existingRecord->upload_id = $fileId;
                                $existingRecord->save();
                            } else {
                                // do nothing...
                            }

                        }

                    } else {

                        /* create the record */
                        AdultSocialCareServices::create([
                            'upload_id' => $fileId,
                            'asc_id' => $row['ascid'] ?? null,
                            'address_1' => $row['address1'] ?? null,
                            'address_2' => $row['address2'] ?? null,
                            'address_3' => $row['address3'] ?? null,
                            'town' => $row['town'] ?? null,
                            'county' => $row['county'] ?? null,
                            'postcode' => $row['postcode'] ?? null,
                            'nhs_no' => $row['nhsno'] ?? null,
                            'first_name' => $row['firstname'] ?? null,
                            'surname' => $row['surname'] ?? null,
                            'dob' => $row['dob'] ?? null,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'cost' => $row['cost'] ?? null,
                            'frequency' => $row['frequency'] ?? null,
                            'service' => $row['service'] ?? null,
                            'service_type' => $row['servicetype'] ?? null,
                            'primary_support_reason_category' => $row['primarysupportreasoncategory'] ?? null,
                            'care_package_line_item_id' => $row['carepackagelineitemid'] ?? null
                        ]);
                    }

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
        AdultSocialCareServices::where('upload_id', $this->uploadedFile->id)->delete();
        $uploadLogRecord = Upload_log::find($this->uploadedFile->id);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 0;
        $uploadLogRecord->error_msg = substr($exception->getMessage(), 0, 225);
        $uploadLogRecord->save();
        $this->deleteFile();
    }

    public function deleteFile()
    {
        Storage::delete($this->uploadedFile->path);
    }

    public function checkMandatoryColumns($columns, $row)
    {
        foreach ($columns as $column) {
            if ($row[$column]) {

            } else {
                return false;
            }
        }
        return true;
    }
}
