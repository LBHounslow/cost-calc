@extends('layouts/app')

@section('title')
    <a href="/settings">Settings</a> > File Types
@endsection

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
            <tr>
                <th>Display Name</th>
                <th>Script Path</th>
                <th>Model Path</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach ($fileTypes as $fileType)
                <tr>
                    <td>{{ $fileType->display_name }}</td>
                    <td>{{ $fileType->importScript->script_path }}</td>
                    <td>{{ $fileType->importModel->model_path }}</td>
                    <td>
                        <a href="/filetype/{{ $fileType->id }}/edit">
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

    <a href="/filetype/create">
        <button class="btn btn-primary">Create New File Type</button>
    </a>

@stop