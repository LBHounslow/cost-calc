<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use Exception;
use App\TroubledFamilies;
use Excel;
use App\Upload_log;

class TemplateImportScript implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $uploadedFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }

    public function markAsProcessed($fileId)
    {
        $uploadLogRecord = Upload_log::find($fileId);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 1;
        $uploadLogRecord->save();
    }

    public function insertClients()
    {
        $model = new $this->uploadedFile->fileType->importModel->model_path();
        $importTable = $model->getTable();


        $sql = <<<EOT
INSERT INTO [dbo].[clients]
        (surname, dob, postcode, created_at, updated_at)

SELECT DISTINCT
        imp.surname, imp.dob, imp.postcode, GETDATE(), GETDATE()
FROM
        $importTable imp
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
        $importTable AS imp
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
        $this->uploadedFile->FileType->importModel->model_path::where('upload_id', $this->uploadedFile->id)->delete();
        //HousingTempAccom::where('upload_id', $this->uploadedFile->id)->delete();
        $uploadLogRecord = Upload_log::find($this->uploadedFile->id);
        $uploadLogRecord->processed = 1;
        $uploadLogRecord->status = 0;
        $uploadLogRecord->msg = substr($exception->getMessage(), 0, 225);
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
