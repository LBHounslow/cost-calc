@extends('layouts/app')

@section('title')
    <a href="/settings">Settings</a> > User Change Log
@endsection

@section('content')

    <table class="table" id="user-change-table"
           data-toggle="table"
           data-show-export="true"
           data-search="true"

           data-pagination="true"
           data-show-multi-sort="true"
           data-show-columns="true">
        <thead>
        <tr>
            <th data-field="id" data-sortable="true">Change Id</th>
            <th data-field="user_id" data-sortable="true">User Id</th>
            <th data-field="from" data-sortable="true">Old Value</th>
            <th data-field="to" data-sortable="true">New Value</th>
            <th data-field="change_user_id" data-sortable="true">Changed By User Id</th>
            <th data-field="created_at" data-sortable="true">Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($userChanges as $userChange)
            <tr>
                <td>{{ $userChange->id }}</td>
                <td>{{ $userChange->user_id }}</td>
                <td>{{ $userChange->from }}</td>
                <td>{{ $userChange->to }}</td>
                <td>{{ $userChange->change_user_id }}</td>
                <td>{{ $userChange->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('headerScripts')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">
@endsection

@section('footerScripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="/js/tableExport.js"></script>
    <script>
        $('#user-change-table').bootstrapTable({
            exportDataType: 'all',
        });
    </script>
@endsection