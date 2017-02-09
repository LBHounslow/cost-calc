<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use Exception;
use App\HousingTempAccom;
use Excel;
use App\Upload_log;

class ImportTempAccom implements ShouldQueue
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

                HousingTempAccom::create([
                    'upload_id' => $fileId,
                    'pin' => $row['pin'],
                    'address_1' => $row['address1'],
                    'address_2' => $row['address2'],
                    'address_3' => $row['address3'],
                    'address_4' => $row['address4'],
                    'postcode' => $row['postcode'],
                    'ni' => $row['ni'],
                    'first_name' => $row['firstname'],
                    'surname' => $row['surname'],
                    'dob' => $row['dob'],
                    'residents' => $row['residents'],
                    'start_date' => $row['startdate'],
                    'end_date' => $row['enddate'],
                    'weekly_cost' => $row['cost'],
                    'prop_type' => $row['proptype'],
                    'prop_sub_type' => $row['propsubtype'],
                ]);

            });
        });

        $uploadLogRecord = Upload_log::find($fileId);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 1;
        $uploadLogRecord->save();

        $sql = <<<EOT
INSERT INTO [dbo].[clients]
        (surname, dob, postcode, created_at, updated_at)

SELECT DISTINCT
        imp.surname, imp.dob, imp.postcode, GETDATE(), GETDATE()
FROM
        [dbo].[import_housing_temp_accom] imp
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
        [dbo].[import_housing_temp_accom] AS imp
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
