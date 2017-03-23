<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use Exception;
use App\HousingBenefitEntitlement;
use Excel;
use App\Upload_log;
use App\Jobs\TemplateImportScript;

class ImportHousingBenefitEntitlement extends TemplateImportScript implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

                //if ($this->checkMandatoryColumns($mandatoryColumns, $row) && $emptyRow === false) {

                // check if we have record already
                /*$existingRecord = HousingBenefitEntitlement::where([
                    ['ni', $row['nino'] ?? null],
                ])->first();*/

                //if (isset($existingRecord->id)) {
                // do nothing
                // } else {

                // create new record
                HousingBenefitEntitlement::create([
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
                    'dob' => $row['claimants_date_of_birth'] ?? null,
                    'start_date' => $row['hb_claim_entitlement_start_date'] ?? null,
                    'end_date' => $row['snapshot_date'] ?? null,
                    'weekly_housing_benefit_entitlement' => $row['weekly_housing_benefit_entitlement'] ?? null,
                    'weekly_eligible_rent_amount' => $row['weekly_eligible_rent_amount'] ?? null,
                    'contractual_rent_amount' => $row['contractual_rent_amount'] ?? null,
                    'time_period_contractual_rent_figure_covers' => $row['time_period_contractual_rent_figure_covers'] ?? null,
                    'tenancy_type' => $row['tenancy_type'] ?? null,
                ]);
                //}

                //} else {
                // do nothing...
                //}
            });
        });

        $this->markAsProcessed($fileId);
        $this->insertClients();
    }
}
