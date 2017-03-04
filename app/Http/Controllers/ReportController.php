<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function index()
    {
        $serviceTypes = $this->getServiceTypes(null, true);
        $spendByClients = $this->getSpendByClient(null, true);
        $spendByServices = $this->getSpendByService(null, true);

        return View::make('reports/index', [
            'clientsTotalSpend' => json_encode($spendByClients),
            'serviceTypes' => $serviceTypes,
            'spendByServicesArr' => $spendByServices,
            'spendByServices' => $spendByServices,
        ]);
    }

    private function splitServiceTypes($services)
    {
        return "'" . str_replace(",", "','", rawurldecode($services)) . "'";
    }

    private function createServiceFilterClause($services)
    {
        $servicesArr = explode(',', rawurldecode($services));

        $i = '1';
        $query = '';

        foreach ($servicesArr as $service) {

            if ($i === '1') {
                $op = 'WHERE';
            } else {
                $op = 'AND';
            }

            $query .= "$op id IN (SELECT id FROM Costs WHERE CONCAT(Costs.service, ' - ', Costs.service_type) = '$service' AND Costs.id = SubQuery.id) ";
            $i++;
        }
        return $query;
    }

    private function executeQuery($query)
    {
        if (env('DB_CONNECTION', false) == 'mysql') {
            return [];
        } else {
            DB::connection()->setFetchMode(\PDO::FETCH_ASSOC);
            $result = DB::select(DB::raw($query));
            return $result;
        }
    }


    public function getSpendByClient(Request $request = null, $allServices = false)
    {

        /* Service Type Filter */
        if (isset($request) && $request->input('serviceType') && $request->input('serviceFilter') === '2') {
            $whereClause = $this->createServiceFilterClause($request->input('serviceType'));
        } elseif (isset($request) && $request->input('serviceType')) {
            $whereClause = "WHERE CONCAT(service, ' - ', service_type) IN (" . $this->splitServiceTypes($request->input('serviceType')) . ")";
        } elseif ($allServices) {
            $whereClause = "WHERE 1=1 ";
        } else {
            $whereClause = "WHERE 1=2 ";
        }

        /* Temp Accom Filter */
        if (isset($request) && $request->input('tempFilter') === '1') {
            // all
            $whereClause .= " AND 1 = 1";
        } elseif (isset($request) && $request->input('tempFilter') === '2') {
            // only temp
            $whereClause .= " AND id IN (select client_id from import_housing_temp_accom)";
        } elseif (isset($request) && $request->input('tempFilter') === '3') {
            // not temp
            $whereClause .= " AND id NOT IN (select client_id from import_housing_temp_accom)";
        } else {
            $whereClause .= " AND 2 = 2";
        }

        /* Troubled Families Filter */
        if (isset($request) && $request->input('troubledFilter') === '1') {
            // all
            $whereClause .= " AND 1 = 1";
        } elseif (isset($request) && $request->input('troubledFilter') === '2') {
            // only temp
            $whereClause .= " AND id IN (select client_id from import_troubled_families)";
        } elseif (isset($request) && $request->input('troubledFilter') === '3') {
            // not temp
            $whereClause .= " AND id NOT IN (select client_id from import_troubled_families)";
        } else {
            $whereClause .= " AND 2 = 2";
        }

        /* Housing Benefit Switch Filter */
        if (isset($request) && $request->input('hbSwitchFilter') === '1') {
            // all
            $whereClause .= " AND 1 = 1";
        } elseif (isset($request) && $request->input('hbSwitchFilter') === '2') {
            // only temp
            $whereClause .= " AND id IN (select client_id from import_housing_benefit_switch)";
        } elseif (isset($request) && $request->input('hbSwitchFilter') === '3') {
            // not temp
            $whereClause .= " AND id NOT IN (select client_id from import_housing_benefit_switch)";
        } else {
            $whereClause .= " AND 2 = 2";
        }

        /* Date Filter */
        if (isset($request) && $request->input('start') && $request->input('end')) {
            $start = $request->input('start');
            $end = $request->input('end');
        } else {
            $start = '2015-04-01';
            $end = '2016-04-01';
        }

        $headerQuery = "SELECT id, CAST(SUM(report_cost) as decimal(10,2)) as 'sum' ";
        $footerQuery = "$whereClause GROUP by id ORDER BY SUM(report_cost) desc;";
        $query = $this->buildQuery($headerQuery, $footerQuery, $start, $end);
        return $this->executeQuery($query);
    }


    public function getSpendByService(Request $request = null, $allServices = false)
    {
        /* Service Type Filter */
        if (isset($request) && $request->input('serviceType') && $request->input('serviceFilter') === '2') {
            $whereClause = $this->createServiceFilterClause();
        } elseif (isset($request) && $request->input('serviceType')) {
            $whereClause = "WHERE CONCAT(service, ' - ', service_type) IN (" . $this->splitServiceTypes($request->input('serviceType')) . ")";
        } elseif ($allServices) {
            $whereClause = "WHERE 1=1 ";
        } else {
            $whereClause = "WHERE 1=2 ";
        }

        /* Temp Accom Filter */
        if (isset($request) && $request->input('tempFilter') === '1') {
            // all
            $whereClause .= " AND 1 = 1";
        } elseif (isset($request) && $request->input('tempFilter') === '2') {
            // only temp
            $whereClause .= " AND id IN (select client_id from import_housing_temp_accom)";
        } elseif (isset($request) && $request->input('tempFilter') === '3') {
            // not temp
            $whereClause .= " AND id NOT IN (select client_id from import_housing_temp_accom)";
        } else {
            $whereClause .= " AND 2 = 2";
        }

        /* Troubled Families Filter */
        if (isset($request) && $request->input('troubledFilter') === '1') {
            // all
            $whereClause .= " AND 1 = 1";
        } elseif (isset($request) && $request->input('troubledFilter') === '2') {
            // only temp
            $whereClause .= " AND id IN (select client_id from import_troubled_families)";
        } elseif (isset($request) && $request->input('troubledFilter') === '3') {
            // not temp
            $whereClause .= " AND id NOT IN (select client_id from import_troubled_families)";
        } else {
            $whereClause .= " AND 2 = 2";
        }

        /* Housing Benefit Switch Filter */
        if (isset($request) && $request->input('hbSwitchFilter') === '1') {
            // all
            $whereClause .= " AND 1 = 1";
        } elseif (isset($request) && $request->input('hbSwitchFilter') === '2') {
            // only temp
            $whereClause .= " AND id IN (select client_id from import_housing_benefit_switch)";
        } elseif (isset($request) && $request->input('hbSwitchFilter') === '3') {
            // not temp
            $whereClause .= " AND id NOT IN (select client_id from import_housing_benefit_switch)";
        } else {
            $whereClause .= " AND 2 = 2";
        }

        /* Date Filter */
        if (isset($request) && $request->input('start') && $request->input('end')) {
            $start = $request->input('start');
            $end = $request->input('end');
        } else {
            $start = '2015-04-01';
            $end = '2016-04-01';
        }

        $headerQuery = "SELECT service, service_type, concat(service, ' - ', service_type) as 'serviceType', CAST(SUM(report_cost) as INT) as 'sum_report_cost', COUNT(1) as 'count' ";
        $footerQuery = "$whereClause GROUP BY service, service_type ORDER BY SUM(report_cost) DESC;";
        $query = $this->buildQuery($headerQuery, $footerQuery, $start, $end);
        //return $query;
        return $this->executeQuery($query);
    }


    public function getClientSpend(Request $request = null, $allServices = false)
    {
        /* Service Type Filter */
        if (isset($request) && $request->input('serviceType') && $request->input('serviceFilter') === '2') {
            $whereClause = $this->createServiceFilterClause();
        } elseif (isset($request) && $request->input('serviceType')) {
            $whereClause = "WHERE CONCAT(service, ' - ', service_type) IN (" . $this->splitServiceTypes($request->input('serviceType')) . ")";
        } elseif ($allServices) {
            $whereClause = "WHERE 1=1 ";
        } else {
            $whereClause = "WHERE 1=2 ";
        }

        /* Date Filter */
        if (isset($request) && $request->input('start') && $request->input('end')) {
            $start = $request->input('start');
            $end = $request->input('end');
        } else {
            $start = '2015-04-01';
            $end = '2016-04-01';
        }

        $headerQuery = "SELECT id, service, service_type, start_date, frequency, convert(date,end_date) as 'end_date', CAST(unit_cost as decimal(10,2)) as 'unit_cost', CAST(report_cost as decimal(10,2)) as 'report_cost' ";
        $footerQuery = "$whereClause AND id = " . $request->input('clientId') . " ORDER BY start_date;";
        $query = $this->buildQuery($headerQuery, $footerQuery, $start, $end);


        //return $query;
        return $this->executeQuery($query);
    }


    public function getServiceTypes()
    {
        $result = DB::select(DB::raw("SELECT DISTINCT service, service_type FROM Costs;"));
        return $result;
    }


    public function buildQuery($headerQuery, $footerQuery, $reportStartDate, $reportEndDate)
    {
        $sql = <<<EOT

            $headerQuery

            FROM
                (
                    SELECT
                        id,
                        surname,
                        dob,
                        postcode,
                        service,
                        service_type,
                        frequency,
                        start_date,
                        end_date,
                        report_start,
                        report_end,
                        unit_cost,
                        CASE frequency
                            WHEN 'Weekly' THEN DATEDIFF ( week , report_start ,  report_end) * unit_cost
                            WHEN 'Annual' THEN DATEDIFF ( year , report_start , report_end) * unit_cost
                            WHEN 'One-off' THEN 1 * unit_cost
                            WHEN 'Intermittent (per Financial Year)' THEN 1 * unit_cost
                            WHEN 'Every 2 weeks' THEN (DATEDIFF ( week , report_start , report_end ) / 2) * unit_cost
                            ELSE 1 * unit_cost
                        END as 'report_cost'

                    FROM
                        (
                            SELECT
                                id,
                                surname,
                                dob,
                                postcode,
                                service,
                                service_type,
                                frequency,
                                start_date,
                                end_date,
                                CASE
                                WHEN '$reportStartDate' < start_date THEN start_date
                                ELSE '$reportStartDate'
                                END as 'report_start',
                                CASE
                                WHEN '$reportEndDate' > end_date THEN end_date
                                ELSE '$reportEndDate'
                                END as 'report_end',
                                unit_cost,
                                CAST(0 as decimal(12,2)) as 'report_cost'
                            FROM
                                Costs
                            WHERE
                                start_date <= '$reportEndDate'
                                AND (end_date >= '$reportStartDate' OR end_date IS NULL)
                        ) as SubSubQuery
                ) as SubQuery

                $footerQuery
EOT;
        return $sql;
    }


}
