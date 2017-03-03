<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use Exception;
use App\HousingBenefitSwitch;
use Excel;
use App\Upload_log;

class ImportHousingBenefitSwitch implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

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
                    'nino',
                    'dob',
                    'lastname',
                    'postcode',
                ];

                if ($this->checkMandatoryColumns($mandatoryColumns, $row) && $emptyRow === false) {

                    // check if we have record already
                    $existingRecord = HousingBenefitSwitch::where([
                        ['ni', $row['nino'] ?? null],
                    ])->first();

                    if (isset($existingRecord->id)) {
                        // do nothing
                    } else {

                        // create new record
                        HousingBenefitSwitch::create([
                            'upload_id' => $fileId,
                            'claim_no' => $row['claimno'] ?? null,
                            'ni' => $row['nino'] ?? null,
                            'title' => $row['title'] ?? null,
                            'first_name' => $row['firstname'] ?? null,
                            'surname' => $row['lastname'] ?? null,
                            'dob' => $row['dob'] ?? null,
                            'postcode' => $row['postcode'] ?? null,
                            'address' => $row['address'] ?? null,
                        ]);
                    }

                } else {
                    // do nothing...
                }
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
        [dbo].[import_housing_benefit_switch] imp
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
        [dbo].[import_housing_benefit_switch] AS imp
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
        HousingTempAccom::where('upload_id', $this->uploadedFile->id)->delete();
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
