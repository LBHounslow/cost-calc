<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients;
use View;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('clients/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Clients::find($id);
        $details = $this->getClientDetails($id);

        return View::make('clients/index', ['client' => $client, 'details' => $details]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getClientDetails($id)
    {
        $query = "
                                SELECT DISTINCT
                                                client_id as 'client_id',
                                                [first_name] as 'first_name', null as 'ni', [nhs_no] as 'nhs_no',
                                                CONCAT([address_1],', ',[address_2],', ',[address_3],', ',[town],', ',[county],', ',[postcode]) as 'full_address'
                                FROM
                                                [import_adult_social_care_services]
                                WHERE
                                                client_id = $id

                                UNION

                                SELECT DISTINCT
                                                client_id as 'client_id',
                                                [first_name] as 'first_name', [ni] as 'ni', null as 'nhs_no',
                                                CONCAT([address_1],', ',[address_2],', ',[address_3],', ',[address_4],', ',[postcode]) as 'full_address'
                                FROM
                                                [import_housing_temp_accom]
                                WHERE
                                                client_id = $id

                                UNION

                                SELECT DISTINCT
                                                client_id as 'client_id',
                                                [first_name] as 'first_name', [ni] as 'ni', [nhs_no] as 'nhs_no',
                                                CONCAT([address_1],', ',[address_2],', ',[address_3],', ',[address_4],', ',[postcode]) as 'full_address'
                                FROM
                                                [import_general_services]
                                WHERE
                                                client_id = $id

                                UNION

                                SELECT DISTINCT
                                                client_id as 'client_id',
                                                [first_name] as 'first_name', [ni] as 'ni', null as 'nhs_no',
                                                CONCAT([address_1],', ',[address_2],', ',[address_3],', ',[address_4],', ',[postcode]) as 'full_address'
                                FROM
                                                [import_housing_benefit_entitle]
                                WHERE
                                                client_id = $id

                                UNION

                                SELECT DISTINCT
                                                client_id as 'client_id',
                                                [first_name] as 'first_name', [ni] as 'ni', null as 'nhs_no',
                                                [address] as 'full_address'
                                FROM
                                                [import_housing_benefit_switch]
                                WHERE
                                                client_id = $id";

        return $this->executeQuery($query);
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
}