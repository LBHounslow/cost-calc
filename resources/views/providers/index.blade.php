@extends('layouts/app')

@section('title')
    Providers
@endsection

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
            <tr>
                <th>Display Name</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach ($providers as $provider)
                <tr>
                    <td>{{ $provider->display_name }}</td>
                    <td>
                        <a href="/provider/{{ $provider->id }}/edit">
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

    <a href="/provider/create">
        <button class="btn btn-primary">Create New Provider</button>
    </a>

@stop