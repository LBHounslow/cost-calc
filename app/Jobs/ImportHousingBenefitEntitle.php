<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use Exception;
use App\HousingBenefitEntitle;
use Excel;
use App\Upload_log;
use App\Jobs\TemplateImportScript;
use Carbon\Carbon;

class ImportHousingBenefitEntitle extends TemplateImportScript implements ShouldQueue
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
                    'housing_benefit_claim_reference_number',
                    'claimants_date_of_birth',
                    'claimants_surname',
                    'claimants_postcode',
                ];

                if ($this->checkMandatoryColumns($mandatoryColumns, $row) && $emptyRow === false) {

                    // check if we have record already
                    $existingRecord = HousingBenefitEntitle::where([
                        ['claim_ref', $row['housing_benefit_claim_reference_number'] ?? null],
                    ])->first();

                    if (isset($existingRecord->id)) {

                        $newDate = new Carbon($row['snapshot_date']);
                        $existDate = new Carbon($existingRecord->end_date);

                        if ($newDate->gt($existDate)) {

                            // update end date
                            $existingRecord->end_date = $newDate;
                            $existingRecord->upload_id = $fileId;
                            $existingRecord->save();

                        } else {
                            // do nothing...
                        }

                    } else {

                        $dob = new Carbon($row['claimants_date_of_birth']);
                        $startDate = new Carbon($row['hb_claim_entitlement_start_date']);
                        $endDate = new Carbon($row['snapshot_date']);

                        // create new record
                        HousingBenefitEntitle::create([
                            'upload_id' => $fileId,
                            'claim_ref' => $row['housing_benefit_claim_reference_number'] ?? null,
                            'address_1' => $row['claimants_address_line_1'] ?? null,
                            'address_2' => $row['claimants_address_line_2'] ?? null,
                            'address_3' => $row['claimants_address_line_3'] ?? null,
                            'address_4' => $row['claimants_address_line_4'] ?? null,
                            'postcode' => $row['claimants_postcode'] ?? null,
                            'ni' => $row['claimants_national_insurance_number'] ?? null,
                            'title' => $row['claimants_title'] ?? null,
                            'first_name' => $row['claimants_first_forename'] ?? null,
                            'surname' => $row['claimants_surname'] ?? null,
                            'dob' => $dob ?? null,
                            'start_date' => $startDate ?? null,
                            'end_date' => $endDate ?? null,
                            'weekly_housing_benefit_entitlement' => $row['weekly_housing_benefit_entitlement'] ?? null,
                            'weekly_eligible_rent_amount' => $row['weekly_eligible_rent_amount'] ?? null,
                            'contractual_rent_amount' => $row['contractual_rent_amount'] ?? null,
                            'time_period_contractual_rent_figure_covers' => $row['time_period_contractual_rent_figure_covers'] ?? null,
                            'tenancy_type' => $row['tenancy_type'] ?? null,
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
