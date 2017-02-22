@extends('layouts/app')

@section('title')
    Edit User
@endsection

@section('content')

    <form class="form-horizontal" role="form" method="POST" action="/user/{{ $user->id }}/update">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Name</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" autofocus>
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>
            </div>
        </div>

        <div class="form-group{{ $errors->has('provider') ? ' has-error' : '' }}">
            <label for="provider" class="col-md-4 control-label">Provider</label>

            <div class="col-md-6">
                <select class="form-control" name="provider" id="provider" required>

                    @foreach($providers as $provider)
                        <option {{ $user->provider_code == $provider->code ? ' selected="selected"' : '' }}
                                value="{{ $provider->code }}">{{ $provider->display_name }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>

        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
            <label for="permissions" class="col-md-4 control-label">Permissions</label>

            <div class="col-md-6">
                <select multiple class="form-control multiselect" name="permissions[]" id="permissions" required>

                    @foreach($permissions as $permission)
                        <option {{ in_array($permission->permission, json_decode($user->permissions)) ? ' selected="selected"' : ''  }}
                                value="{{ $permission->permission }}">{{ $permission->display_name }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>


        <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
            <label for="active" class="col-md-4 control-label">Status</label>

            <div class="col-md-6">
                <input style="width: 20px;" id="active" type="checkbox" class="form-control" name="active" value="1"
                       @if ($user->active === '1') checked @endif >
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/users" class="btn btn-default" role="button">Cancel</a>
            </div>
        </div>

    </form>

@stop

@section('headerScripts')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"/>
    @endsection


    @section('footerScripts')

            <!-- Multi Select plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
    <script type="text/javascript">

        /* Initiate Multi Select Plugin */
        $('.multiselect').multiselect({
            includeSelectAllOption: true
        });
    </script>

@endsection
