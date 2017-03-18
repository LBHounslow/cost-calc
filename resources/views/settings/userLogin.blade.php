@extends('layouts/app')

@section('title')
    <a href="/settings">Settings</a> > User Login Log
@endsection

@section('content')

    <table class="table" id="user-login-table"
           data-toggle="table"
           data-show-export="true"
           data-search="true"
           data-show-refresh="true"
           data-pagination="true"
           data-show-multi-sort="true"
           data-show-columns="true">
        <thead>
        <tr>
            <th data-field="id" data-sortable="true">Login Id</th>
            <th data-field="user_id" data-sortable="true">User Id</th>
            <th data-field="login_user_name" data-sortable="true">Username</th>
            <th data-field="login_user_email" data-sortable="true">Email</th>
            <th data-field="login_client_ip" data-sortable="true">IP Address</th>
            <th data-field="created_at" data-sortable="true">Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($userLoginRequests as $userLoginRequest)
            @if($userLoginRequest->success == '0')
                <tr class="danger">
            @elseif($userLoginRequest->success == '1')
                <tr class="success">
            @else
                <tr>
                    @endif
                    <td>{{ $userLoginRequest->id }}</td>
                    <td>{{ $userLoginRequest->user_id }}</td>
                    <td>{{ $userLoginRequest->login_user_name }}</td>
                    <td>{{ $userLoginRequest->login_user_email }}</td>
                    <td>{{ $userLoginRequest->login_client_ip }}</td>
                    <td>{{ $userLoginRequest->created_at }}</td>
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
        $('#user-login-table').bootstrapTable({
            exportDataType: 'all',
        });
    </script>
@endsection