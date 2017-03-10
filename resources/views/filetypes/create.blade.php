@extends('layouts/app')

@section('title')
    Create New File Type
@endsection

@section('content')

    <form class="form-horizontal" role="form" method="POST" action="/filetype/store">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('displayName') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Display Name</label>

            <div class="col-md-6">
                <input id="displayName" type="text" class="form-control" name="displayName"
                       value="{{ old('displayName') }}" required
                       autofocus>
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('scriptPath') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">Import Script Path</label>

            <div class="col-md-6">
                <select class="form-control" name="scriptPath" id="scriptPath" required>
                    @foreach($importScripts as $importScript)
                        <option value="{{ $importScript->id }}" {{ $importScript->id == 5 ? ' selected="selected"' : '' }}>{{ $importScript->script_path }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group{{ $errors->has('modelPath') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">Model Path</label>

            <div class="col-md-6">
                <select class="form-control" name="modelPath" id="modelPath" required>
                    @foreach($importModels as $importModel)
                        <option value="{{ $importModel->id }}" {{ $importModel->id == 5 ? ' selected="selected"' : '' }}>{{ $importModel->model_path }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="/filetypes" class="btn btn-default" role="button">Cancel</a>
            </div>
        </div>
    </form>

@endsection
