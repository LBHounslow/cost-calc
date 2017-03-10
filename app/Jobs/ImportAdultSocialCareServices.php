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
use App\Jobs\TemplateImportScript;

class ImportAdultSocialCareServices extends TemplateImportScript implements ShouldQueue
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

        $this->markAsProcessed($fileId);
        $this->insertClients();
    }
    
}
