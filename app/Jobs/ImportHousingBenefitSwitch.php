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
use App\Jobs\TemplateImportScript;

class ImportHousingBenefitSwitch extends TemplateImportScript implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


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

        $this->markAsProcessed($fileId);
        $this->insertClients();
    }


}
