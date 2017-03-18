@extends('layouts/app')

@section('title')
    Settings
@endsection

@section('content')

    <div class="btn-group-vertical settingsNav" role="group">

        <a class="btn btn-default" href="/users" role="button" style="text-align:left;">
            <i class="fa fa-users"></i> User Administration
        </a>

        <a class="btn btn-default" href="/providers" role="button" style="text-align:left;">
            <i class="fa fa-list-alt"></i> Providers
        </a>

        <a class="btn btn-default" href="/filetypes" role="button" style="text-align:left;">
            <i class="fa fa-file-excel-o"></i> File Types
        </a>

        <a class="btn btn-default" href="/settings/login-logs" role="button" style="text-align:left;">
            <i class="fa fa-sign-in"></i> User Login Log
        </a>

        <a class="btn btn-default" href="/settings/change-logs" role="button" style="text-align:left;">
            <i class="fa fa-sign-in"></i> User Change Log
        </a>

    </div>

@stop