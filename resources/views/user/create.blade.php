@extends('layouts/app')

@section('title')
    Create New User
@endsection

@section('content')

    <form class="form-horizontal" role="form" method="POST" action="/user/store">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Name</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                       autofocus>
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>
        </div>

        <div class="form-group{{ $errors->has('provider') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">Provider</label>

            <div class="col-md-6">
                <select class="form-control" name="provider" id="provider" required>
                    @foreach($providers as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->display_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
            <label for="permissions" class="col-md-4 control-label">Permissions</label>

            <div class="col-md-6">
                <select multiple class="form-control multiselect" name="permissions[]" id="permissions" required>

                    @foreach($permissions as $permission)
                        <option value="{{ $permission->permission }}">{{ $permission->display_name }}</option>
                    @endforeach

                </select>
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Password</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="/users" class="btn btn-default" role="button">Cancel</a>
            </div>
        </div>
    </form>

@endsection
