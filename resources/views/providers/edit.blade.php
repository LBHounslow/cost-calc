@extends('layouts/app')

@section('title')
    Edit Provider
@endsection

@section('content')


    @php
    $readyOnlyArr = [1];
    if(in_array($provider->id, $readyOnlyArr)){
    $editable = 'disabled';
    } else {
    $editable = '';
    }
    @endphp


    <form class="form-horizontal" role="form" method="POST" action="/provider/{{ $provider->id }}/update">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('displayName') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Display Name</label>

            <div class="col-md-6">
                <input id="displayName" type="text" class="form-control" name="displayName"
                       value="{{ $provider->display_name }}" required
                       autofocus>
            </div>
        </div>


        <div class="form-group{{ $errors->has('allowedFileTypes') ? ' has-error' : '' }}">
            <label for="allowedFileTypes" class="col-md-4 control-label">Allowed File Types</label>

            <div class="col-md-6">
                <select multiple class="form-control multiselect" name="allowedFileTypes[]" id="allowedFileTypes"
                        required>
                    @foreach($fileTypes as $fileType)
                        <option {{ in_array($fileType->id, json_decode($provider->allowed_file_types)) ? ' selected="selected"' : ''  }}
                                value="{{ $fileType->id }}">{{ $fileType->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/providers" class="btn btn-default" role="button">Cancel</a>
            </div>
        </div>
    </form>

@endsection
