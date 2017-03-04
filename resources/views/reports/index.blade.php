@extends('layouts/app')

@section('title')
    Reports
    @endsection

    @section('content')

            <!-- Filter -->
    <h3>Filter</h3>
    <form>
        <div class="form-group">
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control" name="start"/>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm form-control" name="end"/>
            </div>
        </div>
        <div class="form-group">
            <select multiple id="service-type-select" class="form-control multiselect">
                @foreach ($serviceTypes as $serviceType)
                    <option value="{{ $serviceType->service }} - {{  $serviceType->service_type }}" selected="selected">
                        {{ $serviceType->service }} - {{  $serviceType->service_type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="radio-inline">
                <input type="radio" name="tempFilter" id="inlineRadio1" value="1" checked> All
            </label>
            <label class="radio-inline">
                <input type="radio" name="tempFilter" id="inlineRadio2" value="2"> Temp Accom Only
            </label>
            <label class="radio-inline">
                <input type="radio" name="tempFilter" id="inlineRadio3" value="3"> Not Temp Accom
            </label>
        </div>
        <div class="form-group">
            <label class="radio-inline">
                <input type="radio" name="troubledFilter" id="inlineRadio1" value="1" checked> All
            </label>
            <label class="radio-inline">
                <input type="radio" name="troubledFilter" id="inlineRadio2" value="2"> Troubled Families Only
            </label>
            <label class="radio-inline">
                <input type="radio" name="troubledFilter" id="inlineRadio3" value="3"> Not Troubled Families
            </label>
        </div>
        <div class="form-group">
            <label class="radio-inline">
                <input type="radio" name="hbSwitchFilter" id="inlineRadio1" value="1" checked> All
            </label>
            <label class="radio-inline">
                <input type="radio" name="hbSwitchFilter" id="inlineRadio2" value="2"> Housing Benefit Switch Only
            </label>
            <label class="radio-inline">
                <input type="radio" name="hbSwitchFilter" id="inlineRadio3" value="3"> Not Housing Benefit Switch
            </label>
        </div>
        <button type="button" id="total-spend-btn" class="btn btn-primary">Apply Filter</button>
    </form>

    <hr>

    <!-- Reports -->
    <h3>Reports</h3>
    <div id="myTabs">


        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#client-grouped-tab" aria-controls="client-grouped-tab" role="tab" data-toggle="tab">
                    Total Spend by Client
                </a>
            </li>
            <li role="presentation">
                <a href="#service-tab" aria-controls="service-tab" role="tab" data-toggle="tab">
                    Total Spend by Service
                </a>
            </li>
            <li role="presentation">
                <a href="#client-breakdown-tab" aria-controls="client-breakdown-tab" role="tab" data-toggle="tab">
                    Client Spend Breakdown
                </a>
            </li>
        </ul>


        <!-- Tab panes -->
        <div class="tab-content">


            <!-- Total Spend by Client -->
            <div role="tabpanel" class="tab-pane active" id="client-grouped-tab">
                <br><br>
                <table class="table" id="spend-by-client-table"
                       data-toggle="table"
                       data-show-export="true"
                       data-search="true"
                       data-show-refresh="true"
                       data-pagination="true"
                       data-show-multi-sort="true"
                       data-show-columns="true">
                    <thead>
                    <tr>
                        <th data-radio="true"></th>
                        <th data-field="id" data-sortable="true">Client Id</th>
                        <th data-formatter="formatSpend" data-field="sum" data-sortable="true">Total Spend</th>
                    </tr>
                    </thead>
                </table>
                <br><br>
            </div>


            <!-- Total Spend by Service -->
            <div role="tabpanel" class="tab-pane" id="service-tab">
                <br><br>
                <table class="table" id="spend-by-service-table"
                       data-toggle="table"
                       data-show-export="true"
                       data-search="true"
                       data-show-refresh="true"
                       data-pagination="true"
                       data-show-multi-sort="true"
                       data-show-columns="true">
                    <thead>
                    <tr>
                        <th data-field="serviceType" data-sortable="true">Service</th>
                        <th data-formatter="formatSpend" data-field="sum_report_cost" data-sortable="true">Total Spend
                        </th>
                    </tr>
                    </thead>
                </table>
                <br><br>

                <!-- Pie Chart -->
                <canvas id="spend-by-service-chart" width="400" height="400"></canvas>
            </div>


            <!-- Client Spend Breakdown -->
            <div role="tabpanel" class="tab-pane" id="client-breakdown-tab">
                <br><br>
                <table class="table" id="client-spend-table"
                       data-toggle="table"
                       data-show-export="true"
                       data-search="true"
                       data-show-refresh="true"
                       data-pagination="true"
                       data-show-multi-sort="true"
                       data-show-columns="true">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">
                            Client Id
                        </th>
                        <th data-field="service" data-sortable="true">Service</th>
                        <th data-field="service_type" data-sortable="true">Service Type</th>
                        <th data-field="start_date" data-sortable="true">Start Date</th>
                        <th data-field="end_date" data-sortable="true">End Date</th>
                        <th data-formatter="formatSpend" data-field="unit_cost" data-sortable="true">Cost</th>
                        <th data-field="frequency" data-sortable="true">Cost Frequency</th>
                        <th data-formatter="formatSpend" data-field="report_cost" data-sortable="true">Report Cost</th>
                    </tr>
                    </thead>
                </table>
                <br><br>

                <!-- Pie Chart -->
                <canvas id="client-spend-chart" width="400" height="400"></canvas>
            </div>


        </div>
    </div>
@endsection


@section('headerScripts')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"/>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css"/>
@endsection

@section('footerScripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
    <script src="/js/tableExport.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>


    <!-- Multi Select plugin && Date Picker -->
    <script type="text/javascript">

        /* Initiate Multi Select Plugin */
        $('.multiselect').multiselect({
            includeSelectAllOption: true
        });

        /* Initiate Date Picker Plugin */
        $('.input-daterange').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,

        });

        /* Set Default Date */
        $(".input-daterange").data("datepicker").pickers[0].setDate("01/04/2015");
        $(".input-daterange").data("datepicker").pickers[1].setDate("01/04/2016");


    </script>


    <!-- Initialize bootstrap tables & Pie Charts -->
    <script>

        /**
         *
         *  Spend by Client
         *
         */

        /* Create Object */
        var spendByClient = <?php echo $clientsTotalSpend; ?>;

        var filterCounter = 0;

        /* Create Table */
        $('#spend-by-client-table').bootstrapTable({
            data: spendByClient,
            exportDataType: 'all',
            onCheck: function (row, element) {
                Spend.getFilter();
                Spend.getClientSpend(row.id, function () {

                    $('#myTabs a[href="#client-breakdown-tab"]').tab('show');

                    if (filterCounter > 0) {
                        clientPieChart.destroy();
                    }
                    clientPieChart.updateData(Spend.clientSpend);
                    clientPieChart.build();
                    filterCounter++;
                });


            }
        });


        /**
         *
         *  Spend by Service
         *
         */

        /* Create Object */
        var spendByService = <?php echo json_encode($spendByServices); ?>;

        /* Create Table */
        $('#spend-by-service-table').bootstrapTable({
            data: spendByService,
            exportDataType: 'all',
        });

        /* Create Pie Chart */
        var servicePieChart = new PieChart('#spend-by-service-chart', spendByService, {
            labelKey: 'serviceType',
            dataKey: 'sum_report_cost'
        }, 'Currency');

        servicePieChart.build();


        /**
         *
         *  Client Spend
         *
         */

        $('#client-spend-table').bootstrapTable({
            data: "",
            exportDataType: 'all',
        });

        var clientPieChart = new PieChart('#client-spend-chart', {}, {
            labelKey: 'service_type',
            dataKey: 'report_cost'
        }, 'Currency');


        function createClientLink(value, row, index) {
            return value + ' <a href="' + baseUrl + '/client/' + value + '">(view details)</a>';
        }

        function formatSpend(value, row, index) {
            return '£' + formatNumber(value);
        }

        function formatNumber(n) {
            return Number(n).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
        }


    </script>

    <!-- Filter has been Submitted  -->
    <script>
        $('#total-spend-btn').click(function () {

            $('#client-spend-table').bootstrapTable('removeAll');
            Spend.getFilter();
            Spend.getSpendByClients(function () {
                if ($('#myTabs .active > a').attr('href') == '#client-breakdown-tab') {
                    $('#myTabs a[href="#client-grouped-tab"]').tab('show');
                }
            });
            Spend.getSpendByServices(function () {
                servicePieChart.destroy();
                servicePieChart.updateData(Spend.spendByServices);
                servicePieChart.build();
            });


        });

    </script>

@endsection