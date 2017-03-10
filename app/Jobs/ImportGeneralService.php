<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use Exception;
use App\GeneralService;
use Excel;
use App\Upload_log;
use App\Jobs\TemplateImportScript;

class ImportGeneralService extends TemplateImportScript implements ShouldQueue
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
                    'postcode',
                    'dob',
                    'surname',
                    'startdate',
                    'servicedesc',
                ];

                if ($this->checkMandatoryColumns($mandatoryColumns, $row) && $emptyRow === false) {

                    // check if we have record already
                    $existingRecord = GeneralService::where([
                        ['surname', $row['surname'] ?? null],
                        ['dob', $row['dob'] ?? null],
                        ['postcode', $row['postcode'] ?? null],
                        ['start_date', $row['startdate'] ?? null],
                        ['service_desc', $row['servicedesc'] ?? null],
                        ['ext_ref', $row['extref'] ?? null],
                    ])->first();

                    if (isset($existingRecord->id)) {

                        if (empty($row['enddate'])) {
                            // do nothing...
                        } else {

                            $newDate = new \Carbon\Carbon($row['enddate']);

                            if (empty($existingRecord->end_date)) {
                                $existDate = new \Carbon\Carbon('1980-01-01');
                            } else {
                                $existDate = new \Carbon\Carbon($existingRecord->end_date);
                            }
                            if ($newDate->gt($existDate)) {
                                // update end date
                                $existingRecord->end_date = $row['enddate'];
                                $existingRecord->upload_id = $fileId;
                                $existingRecord->save();
                            } else {
                                // do nothing...
                            }

                        }

                    } else {

                        // create new record
                        GeneralService::create([
                            'upload_id' => $fileId,
                            'ext_ref' => $row['extref'] ?? null,
                            'address_1' => $row['address1'] ?? null,
                            'address_2' => $row['address2'] ?? null,
                            'address_3' => $row['address3'] ?? null,
                            'address_4' => $row['address4'] ?? null,
                            'postcode' => $row['postcode'] ?? null,
                            'ni' => $row['ni'] ?? null,
                            'nhs_no' => $row['nhsno'] ?? null,
                            'first_name' => $row['firstname'] ?? null,
                            'surname' => $row['surname'] ?? null,
                            'dob' => $row['dob'] ?? null,
                            'start_date' => $row['startdate'] ?? null,
                            'end_date' => $row['enddate'] ?? null,
                            'cost' => $row['cost'] ?? null,
                            'cost_frequency' => $row['costfrequency'] ?? null,
                            'service_desc' => $row['servicedesc'] ?? null,
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
