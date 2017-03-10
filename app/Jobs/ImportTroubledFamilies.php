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
use App\Jobs\TemplateImportScript;

class ImportTroubledFamilies extends TemplateImportScript implements ShouldQueue
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
                    'individualid',
                    'lastname',
                    'dob',
                    'postcode',
                ];

                if ($this->checkMandatoryColumns($mandatoryColumns, $row) && $emptyRow === false && strtotime($row['dob'])) {

                    // check if we have record already
                    $existingRecord = TroubledFamilies::where([
                        ['individual_id', $row['individualid'] ?? null],
                        ['surname', $row['lastname'] ?? null],
                        ['dob', $row['dob'] ?? null],
                        ['postcode', $row['postcode'] ?? null],
                    ])->first();

                    if (isset($existingRecord->id)) {

                        // do nothing...

                    } else {

                        // create new record
                        TroubledFamilies::create([
                            'upload_id' => $fileId,
                            'individual_id' => $row['individualid'] ?? null,
                            'family_id' => $row['familyid'] ?? null,
                            'first_name' => $row['firstname'] ?? null,
                            'surname' => $row['lastname'] ?? null,
                            'dob' => $row['dob'] ?? null,
                            'address_1' => $row['address_1'] ?? null,
                            'address_2' => $row['address_2'] ?? null,
                            'postcode' => $row['postcode'] ?? null,
                            'uprn' => $row['uprn'] ?? null,
                            'local_uprn' => $row['localuprn'] ?? null,
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
