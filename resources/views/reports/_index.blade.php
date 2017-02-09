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
        <button type="button" id="total-spend-btn" class="btn btn-primary">Submit</button>
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
                        <th data-field="id" data-sortable="true">client id</th>
                        <th data-field="sum" data-sortable="true">total spend</th>
                    </tr>
                    </thead>
                </table>
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
                        <th data-field="service" data-sortable="true">service</th>
                        <th data-field="sum_report_cost" data-sortable="true">sum_report_cost</th>
                    </tr>
                    </thead>
                </table>
                <br><br>
                <input id="toggle-event" type="checkbox" checked data-toggle="toggle" data-on="Sum" data-off="Count"
                       data-onstyle="primary"
                       data-offstyle="primary">

                <canvas id="myChart" width="400" height="400"></canvas>
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
                        <th data-field="id" data-sortable="true">client id</th>
                        <th data-field="service" data-sortable="true">service</th>
                        <th data-field="service_type" data-sortable="true">service_type</th>
                        <th data-field="start_date" data-sortable="true">start_date</th>
                        <th data-field="end_date" data-sortable="true">end_date</th>
                        <th data-field="frequency" data-sortable="true">frequency</th>
                        <th data-field="unit_cost" data-sortable="true">unit_cost</th>
                        <th data-field="report_cost" data-sortable="true">report_cost</th>
                    </tr>
                    </thead>
                </table>
            </div>


        </div>
    </div>
@endsection


@section('headerScripts')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css"/>
@endsection

@section('footerScripts')

    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/extensions/export/bootstrap-table-export.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>

    <!-- Initialize multi select plugin -->
    <script type="text/javascript">
        $('.multiselect').multiselect({
            includeSelectAllOption: true
        });
    </script>

    <!-- Initiate Date Picker -->
    <script>
        $('.input-daterange').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,

        });
        $(".input-daterange").data("datepicker").pickers[0].setDate("01/04/2015");
        $(".input-daterange").data("datepicker").pickers[1].setDate("01/04/2016");
    </script>

    <!-- Filter Functions -->
    <script>
        function getDateRange() {
            startDate = $(".input-daterange").data("datepicker").pickers[0].getUTCDate();
            endDate = $(".input-daterange").data("datepicker").pickers[1].getUTCDate();

            return {
                "startDate": startDate,
                "endDate": endDate
            };
        }

        function getServiceTypes() {
            return $('#service-type-select').val();
        }

        function getTempAccomFilter() {
            return $('input[name=tempFilter]:checked').val();
        }
    </script>

    <!-- color codes -->
    <script>
        var colorCodes = getColorCodes();
    </script>

    <!-- Initialize bootstrap table of client spend -->
    <script>
        var clientTotalSpend = @php echo
        $clientsTotalSpend;
        @endphp ;

        var spendByService = @php echo
        json_encode($spendByServices);
        @endphp ;


        $('#client-breakdown').bootstrapTable({
            data: "",
            exportDataType: 'all'
        });

        $('#spend-by-service-table').bootstrapTable({
            data: spendByService,
            exportDataType: 'all'
        });




        $('#clients-total-spend').bootstrapTable({
            data: clientTotalSpend,
            exportDataType: 'all',
            onCheck: function (row, element) {

                dateRange = getDateRange();
                endDate = moment(dateRange.endDate).format('YYYY-MM-DD');
                startDate = moment(dateRange.startDate).format('YYYY-MM-DD');

                $.ajax({
                    type: "GET",
                    url: "{{ secure_url('/') }}/reports/client-spend?clientId=" + row.id + "&start=" + startDate + "&end=" + endDate,
                    dataType: "json",
                    beforeSend: function () {
                        $('#myTabs a[href="#client-breakdown-tab"]').tab('show');
                        $('#client-breakdown').bootstrapTable('removeAll');
                    },
                    success: function (response) {
                        $('#client-breakdown').bootstrapTable('load', response);
                    },
                });
            }
        });

    </script>

    <!-- AJAX call if user does filter  -->
    <script>
        $('#total-spend-btn').click(function () {

            val = $('#service-type-select').val();
            uri = encodeURI(val);
            tempAccom = $('input[name=tempFilter]:checked').val();

            dateRange = getDateRange();
            endDate = moment(dateRange.endDate).format('YYYY-MM-DD');
            startDate = moment(dateRange.startDate).format('YYYY-MM-DD');

            $.ajax({
                type: "GET",
                url: "{{ secure_url('/') }}/reports/spend-by-client?serviceType=" + uri + "&tempFilter=" + tempAccom + "&start=" + startDate + "&end=" + endDate,
                dataType: "json",
                beforeSend: function () {
                    $('#clients-total-spend').bootstrapTable('removeAll');
                    $('#clients-total-spend').bootstrapTable('removeAll');
                },
                success: function (response) {
                    $('#clients-total-spend').bootstrapTable('load', response);

                    getSpendByService();
                    //myPieChart.destroy();
                    //createPieChart("#myChart", labelsArr, dataSum);
                },
            });
        });

        function getSpendByService() {
            val = $('#service-type-select').val();
            uri = encodeURI(val);
            tempAccom = $('input[name=tempFilter]:checked').val();

            dateRange = getDateRange();
            endDate = moment(dateRange.endDate).format('YYYY-MM-DD');
            startDate = moment(dateRange.startDate).format('YYYY-MM-DD');

            $.ajax({
                type: "GET",
                url: "{{ secure_url('/') }}/reports/spend-by-service?serviceType=" + uri + "&tempFilter=" + tempAccom + "&start=" + startDate + "&end=" + endDate,
                dataType: "json",
                success: function (response) {

                    labelsArr.length = 0;
                    dataSum.length = 0;
                    dataCount.length = 0;

                    response.forEach(function (element) {
                        labelsArr.push(element.service + " - " + element.service_type);
                        dataSum.push(element.sum_report_cost);
                        dataCount.push(element['count']);
                    });

                    $('#toggle-event').bootstrapToggle('on');
                    myPieChart.destroy();
                    createPieChart("#myChart", labelsArr, dataSum);
                },
            });
        }
    </script>


    <!-- Pie Chart -->
    <script>

        // create global var
        var myPieChart;

        // function to create pie chart
        function createPieChart(htmlId, labelsArr, dataArr) {

            ctx = $(htmlId).get(0).getContext("2d");

            data = {
                labels: labelsArr,
                datasets: [
                    {
                        data: dataArr,
                        backgroundColor: colorCodes,
                        hoverBackgroundColor: colorCodes
                    }]
            };

            myPieChart = new Chart(ctx, {
                type: 'pie',
                data: data,
            });
        }

        // get labels from server
        var labelsArr = [
            @foreach ($spendByServicesArr as $serviceSpend)
                    "{{ $serviceSpend["service"] }} - {{  $serviceSpend["service_type"] }}",
            @endforeach
        ];

        // get sum data from server
        var dataSum = [
            @foreach ($spendByServicesArr as $serviceSpend)
            {{ $serviceSpend["sum_report_cost"] }} ,
            @endforeach
        ];

        // get count data from server
        var dataCount = [
            @foreach ($spendByServicesArr as $serviceSpend)
            {{ $serviceSpend["count"] }} ,
            @endforeach
        ];

        // create initial pie chart (sum)
        createPieChart("#myChart", labelsArr, dataSum);

    </script>

    <!-- SUM / COUNT Toggle -->
    <script>
        $('#toggle-event').change(function () {

            if ($(this).prop('checked')) {
                myPieChart.destroy();
                createPieChart("#myChart", labelsArr, dataSum);
            } else {
                myPieChart.destroy();
                createPieChart("#myChart", labelsArr, dataCount);
            }
        });
    </script>


@endsection