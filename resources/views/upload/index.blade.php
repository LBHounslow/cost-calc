@extends('layouts/app')

@section('title')
    Upload File
@endsection

@section('content')

    <a href="/upload/create">
        <button type="button" class="btn btn-primary">Upload New File</button>
    </a>

    <table class="table" id="user-login-table"
           data-toggle="table"
           data-show-export="true"
           data-search="true"
           data-show-refresh="true"
           data-pagination="true"
           data-show-multi-sort="true"
           data-show-columns="true">
        <thead>
        <tr>
            <th data-field="id" data-sortable="true">File Id</th>
            <th data-field="original_filename" data-sortable="true">Filename</th>
            <th data-field="path" data-sortable="true" data-visible="false">Path</th>
            <th data-field="filetype" data-sortable="true">Filetype</th>
            <th data-field="user_id" data-sortable="true" data-visible="false">User Id</th>
            <th data-field="processed" data-sortable="true" data-visible="false">Processed</th>
            <th data-field="status" data-sortable="true">Status</th>
            <th data-field="error_msg" data-sortable="true">Error Message</th>
            <th data-field="created_at" data-sortable="true">Date</th>
            <th data-sortable="true">Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($uploadedFiles as $uploadedFile)


            @if($uploadedFile->status === 0 || $uploadedFile->deleted === 1)
                <tr class="danger">
            @elseif($uploadedFile->status === 1)
                <tr class="success">
            @else
                <tr>
                    @endif


                    <td>{{ $uploadedFile->id }}</td>
                    <td>{{ $uploadedFile->original_filename }}</td>
                    <td>{{ $uploadedFile->path }}</td>
                    <td>{{ $uploadedFile->filetype }}</td>
                    <td>{{ $uploadedFile->user_id }}</td>
                    <td>{{ $uploadedFile->processed }}</td>
                    <td>{{ $uploadedFile->status }}</td>
                    <td>{{ $uploadedFile->error_msg }}</td>
                    <td>{{ $uploadedFile->created_at }}</td>
                    <td>
                        @if($uploadedFile->deleted === 0 && $uploadedFile->status === 1)
                            <a class="deleteButton" data-href="/upload/destroy/{{ $uploadedFile->id }}"
                               data-toggle="modal"
                               data-target="#confirmDelete">
                                <i class=" fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        @endif
                    </td>

                </tr>
                @endforeach
        </tbody>
    </table>


    <!-- Modal -->
    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Deletion</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the file data?</p>
                    <p>This cannot be reversed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a id="confirmDeleteButton" href="/">
                        <button type="button" class="btn btn-danger">Delete File Data</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection




@section('headerScripts')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">
@endsection

@section('footerScripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/extensions/export/bootstrap-table-export.min.js"></script>


    <script>
        $("#deleteButton").click(function () {
            alert("clicked!");
            var fileId = $(this).attr("fileId");
            $('#confirmDeleteButton').attr('href', '/upload/destroy/' + fileId);
        });

        $('#confirmDelete').on('show.bs.modal', function (e) {
            $(this).find('#confirmDeleteButton').attr('href', $(e.relatedTarget).data('href'));
            //$(this).find('#confirmDeleteButton button').attr('href', $(e.relatedTarget).data('href'));
        });

    </script>

@endsection