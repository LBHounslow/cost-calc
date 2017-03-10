<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\HousingTempAccom;
use Excel;
use App\Jobs\TemplateImportScript;

class ImportTempAccom extends TemplateImportScript implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     *
     * @return void
     */
    public
    function handle()
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
                    'proptype',
                ];

                if ($this->checkMandatoryColumns($mandatoryColumns, $row) && $emptyRow === false) {

                    // check if we have record already
                    $existingRecord = HousingTempAccom::where([
                        ['surname', $row['surname'] ?? null],
                        ['dob', $row['dob'] ?? null],
                        ['postcode', $row['postcode'] ?? null],
                        ['start_date', $row['startdate'] ?? null],
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
                        HousingTempAccom::create([
                            'upload_id' => $fileId,
                            'pin' => $row['pin'] ?? null,
                            'address_1' => $row['address1'] ?? null,
                            'address_2' => $row['address2'] ?? null,
                            'address_3' => $row['address3'] ?? null,
                            'address_4' => $row['address4'] ?? null,
                            'postcode' => $row['postcode'] ?? null,
                            'ni' => $row['ni'] ?? null,
                            'first_name' => $row['firstname'] ?? null,
                            'surname' => $row['surname'] ?? null,
                            'dob' => $row['dob'] ?? null,
                            'residents' => $row['residents'] ?? null,
                            'start_date' => $row['startdate'] ?? null,
                            'end_date' => $row['enddate'] ?? null,
                            'weekly_cost' => $row['cost'] ?? null,
                            'prop_type' => $row['proptype'] ?? null,
                            'prop_sub_type' => $row['propsubtype'] ?? null,
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