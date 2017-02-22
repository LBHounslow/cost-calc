@extends('layouts/app')

@section('title')
    User Administration
@endsection

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Provider</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->provider->display_name }}</td>
                    <td>@if($user->active == '1') <span class="label label-success">Active</span> @else <span
                                class="label label-danger">Suspended</span> @endif</td>
                    <td>
                        <a href="/user/{{ $user->id }}/edit">
                            <button type="submit"
                                    class="btn btn-warning btn-sm">Edit
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

    <a href="/user/create">
        <button class="btn btn-primary">Create New User</button>
    </a>

@stop