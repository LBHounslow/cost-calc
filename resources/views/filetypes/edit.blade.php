@extends('layouts/app')

@section('title')
    Edit File Type
@endsection

@section('content')


    @php
    $readyOnlyArr = [1,2,3,4];
    if(in_array($fileType->id, $readyOnlyArr)){
    $editable = 'disabled';
    } else {
    $editable = '';
    }
    @endphp


    <form class="form-horizontal" role="form" method="POST" action="/filetype/{{ $fileType->id }}/update">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('displayName') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Display Name</label>

            <div class="col-md-6">
                <input id="displayName" type="text" class="form-control" name="displayName"
                       value="{{ $fileType->display_name }}" required
                       autofocus {{ $editable }}>
            </div>
        </div>

        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
            <label for="code" class="col-md-4 control-label">Code</label>

            <div class="col-md-6">
                <input id="code" type="text" class="form-control" name="code" value="{{ $fileType->code }}"
                       required {{ $editable }}>
            </div>
        </div>

        <div class="form-group{{ $errors->has('scriptPath') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">Import Script Path</label>

            <div class="col-md-6">
                <select class="form-control" name="scriptPath" id="scriptPath" required {{ $editable }}>
                    @foreach($importScripts as $importScript)
                        <option {{ $fileType->import_script_id == $importScript->id ? ' selected="selected"' : '' }} value="{{ $importScript->id }}">{{ $importScript->script_path }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group{{ $errors->has('modelPath') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">Model Path</label>

            <div class="col-md-6">
                <select class="form-control" name="modelPath" id="modelPath" required {{ $editable }}>
                    @foreach($importModels as $importModel)
                        <option {{ $fileType->import_model_id == $importModel->id ? ' selected="selected"' : '' }} value="{{ $importModel->id }}">{{ $importModel->model_path }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary" {{ $editable }}>Update</button>
                <a href="/filetypes" class="btn btn-default" role="button">Cancel</a>
            </div>
        </div>
    </form>

@endsection
