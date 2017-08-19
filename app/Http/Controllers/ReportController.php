<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\FileType;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function index()
    {
        $fileTypes = FileType::all();
        $serviceTypes = $this->getServiceTypes(null, true);
        $serviceNeeds = $this->getServiceNeeds(null, true);
        $spendByClients = $this->getSpendByClient(null, true);
        $spendByServices = $this->getSpendByService(null, true);

        return View::make('reports/index', [
            'clientsTotalSpend' => json_encode($spendByClients),
            'serviceTypes' => $serviceTypes,
            'serviceNeeds' => $serviceNeeds,
            'spendByServicesArr' => $spendByServices,
            'spendByServices' => $spendByServices,
            'fileTypes' => $fileTypes,
        ]);
    }

    private function splitServiceTypes($services)
    {
        return "'" . str_replace(",", "','", rawurldecode($services)) . "'";
    }

    private function splitServiceNeeds($needs)
    {
        return "'" . str_replace(",", "','", rawurldecode($needs)) . "'";
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

    private function createServiceNeedClause($needs, $flag)
    {
        if ($flag === '2') {
            $needsArr = explode(',', rawurldecode($needs));

            $i = '1';
            $inQuery = "
            SELECT
                id
            FROM
                Costs c ";

            foreach ($needsArr as $need) {
                if ($i === '1') {
                    $op = 'WHERE';
                } else {
                    $op = 'AND';
                }

                $inQuery .= "$op id IN (SELECT id FROM Costs c WHERE c.need = '$need')";
                $i++;
            }
        } else {
            $needsArr = explode(',', rawurldecode($needs));
            $i = '1';
            $needsQuery = '';
            foreach ($needsArr as $need) {
                if ($i === '1') {
                    $needsQuery .= "'$need'";
                } else {
                    $needsQuery .= "',$need'";
                }
                $i++;
            }
            $inQuery = "
                SELECT
                    id
                FROM
                    Costs c
                WHERE
                    c.need IN ($needsQuery) ";
        }

        $sql = " AND id IN ($inQuery)";
        return $sql;
    }

    private function executeQuery($query)
    {
        if (env('DB_CONNECTION', false) == 'mysql') {
            //$result = DB::select(DB::raw($query));
            return [];
        } else {
            DB::connection()->setFetchMode(\PDO::FETCH_ASSOC);
            $result = DB::select(DB::raw($query));
            return $result;
        }
    }

    private function createFileTypeFilterClause($fileTypeFilters)
    {
        $fileTypeFilters = json_decode($fileTypeFilters);
        $sql = '';
        foreach ($fileTypeFilters as $fileTypeFilter) {

            $fileType = FileType::find($fileTypeFilter->key);
            $displayName = $fileType->display_name;
            $model = new $fileType->importModel->model_path();
            $importTable = $model->getTable();

            if ($fileTypeFilter->key === '5') {
                $inQuery = "
                    SELECT
                        client_id
                    FROM
                        $importTable t
                    INNER JOIN
                        upload_log u ON u.id = t.upload_id
                    INNER JOIN
                        file_types f ON f.id = u.filetype
                    WHERE
                        f.display_name = '$displayName' ";
            } else {
                $inQuery = " SELECT client_id FROM $importTable t";
            }

            if (isset($fileTypeFilter->val) && $fileTypeFilter->val === '1') {
                // all
                $sql .= " AND 1 = 1";
            } elseif (isset($fileTypeFilter->val) && $fileTypeFilter->val === '2') {
                // only temp
                $sql .= " AND id IN ($inQuery)";
            } elseif (isset($fileTypeFilter->val) && $fileTypeFilter->val === '3') {
                // not temp
                $sql .= " AND id NOT IN ($inQuery)";
            } else {
                $sql .= " AND 2 = 2";
            }

        }

        return $sql;
    }


    public function getSpendByClient(Request $request = null, $allServices = false)
    {

        /* Service Type Filter */
        if (isset($request) && $request->input('serviceType') && $request->input('serviceFilter') === '2') {
            $whereClause = $this->createServiceFilterClause($request->input('serviceType'));
        } elseif (isset($request) && $request->input('serviceType')) {
            $whereClause = "WHERE CONCAT(service, ' - ', service_type) IN (" . $this->splitServiceTypes($request->input('serviceType')) . ") ";
        } elseif ($allServices) {
            $whereClause = "WHERE 1=1 ";
        } else {
            $whereClause = "WHERE 1=2 ";
        }


        /* Service Need Filter */
        if (isset($request) && $request->input('serviceNeed')) {
            $whereClause .= $this->createServiceNeedClause($request->input('serviceNeed'), $request->input('needFilter'));
        }

        /* File Type Filter */
        if (isset($request) && $request->input('fileTypeFilter')) {
            $whereClause .= $this->createFileTypeFilterClause($request->input('fileTypeFilter'));
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
            $whereClause = $this->createServiceFilterClause($request->input('serviceType'));
        } elseif (isset($request) && $request->input('serviceType')) {
            $whereClause = "WHERE CONCAT(service, ' - ', service_type) IN (" . $this->splitServiceTypes($request->input('serviceType')) . ")";
        } elseif ($allServices) {
            $whereClause = "WHERE 1=1 ";
        } else {
            $whereClause = "WHERE 1=2 ";
        }

        /* Service Need Filter */
        if (isset($request) && $request->input('serviceNeed')) {
            $whereClause .= $this->createServiceNeedClause($request->input('serviceNeed'), $request->input('needFilter'));
        }

        /* File Type Filter */
        if (isset($request) && $request->input('fileTypeFilter')) {
            $whereClause .= $this->createFileTypeFilterClause($request->input('fileTypeFilter'));
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
            $whereClause = $this->createServiceFilterClause($request->input('serviceType'));
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

        $headerQuery = "SELECT id, service, service_type, need, start_date, frequency, convert(date,end_date) as 'end_date', CAST(unit_cost as decimal(10,2)) as 'unit_cost', CAST(report_cost as decimal(10,2)) as 'report_cost' ";
        $footerQuery = "$whereClause AND id = " . $request->input('clientId') . " ORDER BY start_date;";
        $query = $this->buildQuery($headerQuery, $footerQuery, $start, $end);


        //return $query;
        return $this->executeQuery($query);
    }


    public function getServiceTypes()
    {
        $result = DB::select(DB::raw("SELECT DISTINCT service, service_type FROM Costs ORDER BY service, service_type ASC;"));
        return $result;
    }

    public function getServiceNeeds()
    {
        $result = DB::select(DB::raw("SELECT DISTINCT need FROM Costs WHERE need IS NOT NULL ORDER BY need ASC;"));
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
                        need,
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
                                need,
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
