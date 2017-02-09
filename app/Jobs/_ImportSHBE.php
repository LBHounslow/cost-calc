<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Exception;
use App\HousingSHBE;
use Excel;
use App\Upload_log;

class ImportSHBE implements ShouldQueue
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
        $uploadedFile = $this->uploadedFile->uploadedFile;
        $filePath = '/storage/app/' . $uploadedFile['path'];

        Excel::selectSheetsByIndex(0)->load($filePath, function ($reader) use ($uploadedFile) {

            $reader->each(function ($row) use ($uploadedFile) {

                // check if it is an empty row
                foreach ($row as $column) {

                    if ($column == NULL) {
                        $emptyRow = true;
                    } else {
                        $emptyRow = false;
                        break;
                    }
                }

                HousingSHBE::create([
                    'upload_id' => $uploadedFile['id'],
                    'nino' => $row['nino'],
                    'surname' => $row['surname'],
                    'firstname' => $row['firstname'],
                    'dob' => $row['dob'],
                    'address1' => $row['address1'],
                    'address2' => $row['address2'],
                    'address3' => $row['address3'],
                    'address4' => $row['address4'],
                    'postcode' => $row['postcode'],
                    'housingbenefitentitlement' => $row['housingbenefitentitlement'],
                    'eligiblerentamount' => $row['eligiblerentamount'],
                    'contracturalrentamount' => $row['contracturalrentamount'],
                    'startdate' => $row['startdate'],
                    'enddate' => $row['enddate'],
                ]);
            });
        });

        $uploadLogRecord = Upload_log::find($uploadedFile['id']);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 1;
        $uploadLogRecord->save();

        $sql = <<<EOT

INSERT INTO [dbo].[clients]
        (surname, dob, postcode, created_at, updated_at)

SELECT DISTINCT
        imp.surname, imp.dob, imp.postcode, GETDATE(), GETDATE()
FROM
        [dbo].[import_housing_shbe] imp
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
        [dbo].[import_housing_shbe] AS imp
INNER JOIN
        [dbo].[clients] AS c ON c.surname = imp.surname AND c.dob = imp.dob AND c.postcode = imp.postcode;
EOT;


        DB::unprepared(DB::raw($sql));
    }


    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $uploadLogRecord = Upload_log::find($this->uploadedFile->uploadedFile['id']);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 0;
        $uploadLogRecord->error_msg = $exception->getMessage();
        $uploadLogRecord->save();
    }
}