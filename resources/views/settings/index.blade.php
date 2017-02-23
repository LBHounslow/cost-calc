@extends('layouts/app')

@section('title')
    Settings
@endsection

@section('content')

    <div class="btn-group-vertical" role="group">

        <a class="btn btn-default" href="/users" role="button" style="text-align:left;">
            <i class="fa fa-users"></i> User Administration
        </a>

        <a class="btn btn-default" href="/settings/login-logs" role="button" style="text-align:left;">
            <i class="fa fa-sign-in"></i> User Login Log
        </a>

        <a class="btn btn-default" href="/settings/change-logs" role="button" style="text-align:left;">
            <i class="fa fa-sign-in"></i> User Change Log
        </a>

    </div>

@stop